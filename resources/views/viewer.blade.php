<iframe class="video_iframe" id="video-id" height="100%" width="100%"
        src=""
></iframe>
<script src="{{asset('public/js/common.js')}}"></script>
<script>
    let video_element = $('#video-id');
    let url = "{{asset($link)}}";



    @if($version=="scorm_12")
    var API = {};

    (function ($) {
        $(document).ready(function () {
            setupScormApi()
            video_element.attr('src', url)
        });

        function setupScormApi() {
            API.LMSInitialize = LMSInitialize;
            API.LMSGetValue = LMSGetValue;
            API.LMSSetValue = LMSSetValue;
            API.LMSCommit = LMSCommit;
            API.LMSFinish = LMSFinish;
            API.LMSGetLastError = LMSGetLastError;
            API.LMSGetDiagnostic = LMSGetDiagnostic;
            API.LMSGetErrorString = LMSGetErrorString;
        }

        function LMSInitialize(initializeInput) {
            displayLog("LMSInitialize: " + initializeInput);
            return true;
        }

        function LMSGetValue(varname) {
            displayLog("LMSGetValue: " + varname);
            return "";
        }

        function LMSSetValue(varname, varvalue) {
            displayLog("LMSSetValue: " + varname + "=" + varvalue);
            return "";
        }

        function LMSCommit(commitInput) {
            displayLog("LMSCommit: " + commitInput);
            return true;
        }

        function LMSFinish(finishInput) {
            displayLog("LMSFinish: " + finishInput);
            return true;
        }

        function LMSGetLastError() {
            displayLog("LMSGetLastError: ");
            return 0;
        }

        function LMSGetDiagnostic(errorCode) {
            displayLog("LMSGetDiagnostic: " + errorCode);
            return "";
        }

        function LMSGetErrorString(errorCode) {
            displayLog("LMSGetErrorString: " + errorCode);
            return "";
        }

    })(jQuery);
    @else

    var API_1484_11 = {};

    (function ($) {
        $(document).ready(function () {
            setupScormApi();
            video_element.attr('src', url)
        });

        function setupScormApi() {
            API_1484_11.Initialize = Initialize;
            API_1484_11.Commit = Commit;
            API_1484_11.Terminate = Terminate;
            API_1484_11.GetValue = GetValue;
            API_1484_11.SetValue = SetValue;
            API_1484_11.GetErrorString = GetErrorString;
            API_1484_11.GetDiagnostic = GetDiagnostic;
            API_1484_11.GetLastError = GetLastError;
        }

        function Initialize(parameter) {
            displayLog('Initialize ' + parameter)
            return true
        }

        function Commit(parameter) {
            displayLog('Commit ' + parameter)
            return true
        }

        function Terminate(parameter) {
            displayLog('Terminate ' + parameter)
            return true
        }

        function GetValue(name) {
            displayLog('GetValue ' + name)
            return "";
        }

        function SetValue(name, value) {
            displayLog('SetValue ' + name + ' = ' + value)
            return true
        }

        function GetErrorString() {
            displayLog('GetErrorString')
            return ''
        }

        function GetDiagnostic() {
            displayLog('GetDiagnostic')
            return ''
        }

        function GetLastError() {
            displayLog('GetLastError')
            return 0
        }


    })(jQuery);


    @endif


    function displayLog(textToDisplay) {
    }
</script>
