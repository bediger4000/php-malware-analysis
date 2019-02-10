function ExecPhp_setMessage(heading, text)
{
	var message = '<p><strong>' + heading + '</strong> ' + text + '</p>';
	var parent = document.getElementById(execphpCommonL10n.messageContainer);
	try
	{
		container = document.createElement("div");
		container.className = "updated fade";
		container.innerHTML = container.innerHTML + message;
		parent.appendChild(container);
	}
	catch(e) {;}
}
