g_execphp_ajax = new sack();
g_execphp_error_message = "";
g_execphp_retries = 0;
g_execphp_max_retries = 3;
g_execphp_feature = "";

function ExecPhp_subscribeForFeature(feature)
{
	if (g_execphp_feature.length)
		g_execphp_feature += ",";
	g_execphp_feature += feature;
}

function ExecPhp_fillContainer(container_id, text)
{
	var container = document.getElementById(container_id);
	try {container.innerHTML = text;}
	catch (e) {;}
}

function ExecPhp_markContainer(container_id)
{
	var container = document.getElementById(container_id + "-container");
	try {container.style.backgroundColor = "red";}
	catch (e) {;}

}
function ExecPhp_ajaxCompletion()
{
	var edit_others_php = "";
	var switch_themes = "";
	var exec_php = "";

	eval(g_execphp_ajax.response);

	if (!exec_php.length)
		exec_php = "<p>" + execphpAdminL10n.noUserFound + "</p>";
	ExecPhp_fillContainer(execphpAdminL10n.executeArticlesContainer, exec_php);

	if (!switch_themes.length)
		switch_themes = "<p>" + execphpAdminL10n.noUserFound + "</p>";
	ExecPhp_fillContainer(execphpAdminL10n.widgetsContainer, switch_themes);

	if (!edit_others_php.length)
		edit_others_php = "<p>" + execphpAdminL10n.noUserFound + "</p>";
	else
	{
		heading = execphpAdminL10n.securityAlertHeading;
		text = execphpAdminL10n.securityAlertText;
		ExecPhp_setMessage(heading, text);
		ExecPhp_markContainer(execphpAdminL10n.securityHoleContainer);
	}
	ExecPhp_fillContainer(execphpAdminL10n.securityHoleContainer, edit_others_php);
}

function ExecPhp_ajaxError()
{
	g_execphp_error_message += "<br />"
		+ g_execphp_ajax.responseStatus[0] + " " + g_execphp_ajax.responseStatus[1];

	if (g_execphp_retries < g_execphp_max_retries)
	{
		// retry call; sometimes it seems that the AJAX admin script returns 404
		++g_execphp_retries;
		g_execphp_ajax.runAJAX();
	}
	else
	{
		// finally give up after certain amount of retries
		var error_message = "<p>" + execphpAdminL10n.AjaxError + "</p>"
			+ g_execphp_ajax.requestFile + " - " + g_execphp_error_message;

		ExecPhp_markContainer(execphpAdminL10n.executeArticlesContainer);
		ExecPhp_fillContainer(execphpAdminL10n.executeArticlesContainer, error_message);

		ExecPhp_markContainer(execphpAdminL10n.widgetsContainer);
		ExecPhp_fillContainer(execphpAdminL10n.widgetsContainer, error_message);

		ExecPhp_markContainer(execphpAdminL10n.securityHoleContainer);
		ExecPhp_fillContainer(execphpAdminL10n.securityHoleContainer, error_message);

		g_execphp_error_message = "";
		g_execphp_retries = 0;
	}
}

function ExecPhp_requestUser()
{
	g_execphp_ajax.setVar("cookie", document.cookie);
	g_execphp_ajax.setVar("action", execphpAdminL10n.action);
	g_execphp_ajax.setVar("feature", g_execphp_feature);
	g_execphp_ajax.requestFile = execphpAdminL10n.requestFile;
	g_execphp_ajax.onError = ExecPhp_ajaxError;
	g_execphp_ajax.onCompletion = ExecPhp_ajaxCompletion;
	g_execphp_ajax.runAJAX();
	g_execphp_feature = "";
}
