<html>
<head>
    <script type="text/javascript">
        //<![CDATA[
        var GB_SCORM = {
            "content_provider": [],
            "all_ajax_content": [],
            "interaction_index": [],
            "completion_stmt": "",
            "mastery_score": 50,
            "activity_id": "https:\/\/demo.nextsoftwaresolutions.com\/wp-content\/uploads\/grassblade\/46841-test-promt-to-resume-next-time-playing",
            "scorm_version": "1.2",
            "content_name": "Test promt to resume next time playing",
            "ajax_url": "https:\/\/demo.nextsoftwaresolutions.com\/wp-admin\/admin-ajax.php",
            "cache": {
                "cmi.core.score.raw": "",
                "adlcp:masteryscore": "50",
                "cmi.launch_data": "",
                "cmi.suspend_data": "24146070ji1001112a0101201112C10v_player.6dcbBNi0ZPI.6nqdBndDZuy1^1^0000",
                "cmi.core.lesson_location": "",
                "cmi.core.credit": "credit",
                "cmi.core.lesson_status": "incomplete",
                "cmi.core.exit": "suspend",
                "cmi.core.total_time": "0000:00:00",
                "cmi.core.session_time": "",
                "cmi.entry": "resume",
                "cmi.core.entry": "resume",
                "cmi.core.lesson_mode": "normal",
                "cmi.core.student_id": "demo@nextsoftwaresolutions.com",
                "cmi.core.student_name": "demo",
                "cmi.core.content_id": 46841,
                "cmi.core.registration_id": "4ae42eb0-2dfb-47b0-a0c0-e6de65312f82"
            },
            "content_tool": "articulate_storyline",
            "commit_checks": ["cmi.suspend_data", "cmi.core.lesson_status", "cmi.core.lesson_location"]
        };
        var cache = GB_SCORM.cache;
        //]]>
    </script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="{{asset('public/js/scorm-script.js')}}"></script>
    <script>
        var API = function (e) {
            var i = false;
            var a = false;
            var o = GB_SCORM.cache;
            var u = 0;
            var t = this;
            return {
                getCache: function () {
                    return o
                }, LMSInitialize: function (e) {
                    console_log("LMS Initialize");
                    if (a || i) {
                        return "false"
                    }
                    a = true;
                    xapi.initializeAttempt();
                    return "true"
                }, LMSGetValue: function (e) {
                    var t = new Date;
                    var r = t.toLocaleString();
                    if (!a || i) {
                        return "false"
                    }
                    if (e == "cmi.interactions._count") {
                        varvalue_count = parseInt(++u);
                        varvalue = '"' + varvalue_count + '"';
                        return varvalue_count
                    }
                    varvalue = o[e] === undefined ? "" : o[e];
                    return varvalue
                }, LMSSetValue: function (e, t) {
                    console_log({LMSSetValue: e, varvalue: t});
                    var r = new Date;
                    var n = r.toLocaleString();
                    if (!a || i) {
                        return "false"
                    }
                    o["cmi.interactions._count"] = u;
                    o[e] = t;
                    xapi.saveDataValue(e, t);
                    xapi.storeCommitDelayed();
                    return "true"
                }, LMSCommit: function (e) {
                    if (!a || i) {
                        return "false"
                    }
                    console_log("LMS Commit");
                    console_log(o);
                    xapi.storeCommit();
                    return "true"
                }, LMSFinish: function (e) {
                    if (!a || i) {
                        return "false"
                    }
                    console_log("LMS Finish");
                    xapi.storeFinish();
                    return "true"
                }, LMSGetLastError: function () {
                    return 0
                }, LMSGetDiagnostic: function (e) {
                    return "diagnostic string"
                }, LMSGetErrorString: function (e) {
                    return "error string"
                }
            }
        }(window);
        initializeCommunication = API.LMSInitialize;
        terminateCommunication = API.LMSFinish;
        storeDataValue = API.LMSSetValue;
        retrieveDataValue = API.LMSGetValue;
    </script>
    <script type="text/javascript">
        //<![CDATA[
        ADL.XAPIWrapper.changeConfig({
            "endpoint": "https:\/\/test.gblrs.com\/xAPI\/",
            "auth": "Basic MjktYTg0Y2FkNzk4ZDU3NzdiOjU4YzI2MmE3ZjNlYzA0NDk1NGI0NzQzMGM=",
            "registration": "4ae42eb0-2dfb-47b0-a0c0-e6de65312f82",
            "actor": {"mbox": "mailto:demo@nextsoftwaresolutions.com", "name": "demo", "objectType": "Agent"},
            "activity_id": "https:\/\/demo.nextsoftwaresolutions.com\/wp-content\/uploads\/grassblade\/46841-test-promt-to-resume-next-time-playing",
            "isScorm2004": false
        });
        //]]>
    </script>
    <style type="text/css">
        body, frameset, frame {
            width: 100%;
            height: 100%;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body data-new-gr-c-s-check-loaded="14.1058.0" data-gr-ext-installed="">
<iframe frameborder="0" framespacingframespacing="0" border="0" onbeforeunload="API.LMSFinish('');" width="100%"
        height="100%" onunload="API.LMSFinish('');"
        src="{{url(request()->get('src'))}}"
        name="course" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen=""
        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"></iframe>
</body>
</html>
