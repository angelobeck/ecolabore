'flags'={
'modLayout_base'='responsive'
'modLayout_from'='control'
'modLayout_name'='personaliteApearance_layout'
}
'local'={
'scheme'='document document-background-color document-text-color document-border-color text-font-family text-font-size text-line-height'
}
'html'='[script]

// changes dialog tab
tabOld = 2;

function changeTab(tabIndex){
document.getElementById ("tab_content_" + tabOld).style.display = "none";
document.getElementById ("tab_button_" + tabOld).className = "button nav-background-color nav-text-color button-border-radius button-font-family button-font-weight button-font-size button-line-height";
document.getElementById ("tab_content_" + tabIndex).style.display = "block";
document.getElementById ("tab_button_" + tabIndex).className = "button active active-text-color active-background-color button-border-radius button-font-family button-font-weight button-font-size button-line-height";
tabOld = tabIndex;
dialogStyleRefresh ();
}

// submit configurations
function dialogOk(){
document.controls.submit();
}

// Restore default configurations
function dialogRestore(){
document.controls.save.value = ''restore'';
document.controls.submit();
}

// closes dialog window
function dialogClose(){
window.close();
}

// set correct classes to the system bar
document.getElementById ("layout_system_bar").className = "system system-text-color system-background-color bar-height bar-font-family bar-font-weight bar-font-size bar-line-height";
document.getElementById ("layout_system_icons").className = "system-text-color bar-height";

// Updates document apearance
properties = {};

function dialogStyleRefresh (){
for (var currentTarget in properties)
{ // each property
currentProperty = properties[]currentTarget];
target = currentTarget;
value = document.getElementById (currentProperty[]5]).value;
currentProperty[]0] = value;
while (value == "")
{ // find value
value = properties[]target][]0];
if (value == "")
value = properties[]target][]1];
if (value == "")
{
target = properties[]target][]2];
if (target == "")
break;
if (!properties[]target])
break;
}
} // find color

var classList = document.getElementsByClassName (currentProperty[]3]);
var cssProperty = currentProperty[]4];

for (var index = 0; index < classList.length; index++)
{ // loop each html element
classList[]index].style[]cssProperty] = value;
} // loop each html element

var id = currentProperty[]5] + "_link";
var link = document.getElementById (id);
if (link)
link.style.backgroundColor = value;

document.getElementById (currentProperty[]5]) .dataset.current = value;
} // each property
}
[/script]
[mod:fontfaces]

[mod(`formulary`){ list{]
<form id="controls" name="controls" action="[$url]" method="post" enctype="multipart/form-data" charset="[$document.charset]">

<!-- navbar -->
<div style="width:100%;" class="nav nav-text-color nav-background-color nav-border-color nav-border-radius nav-padding">
[paste:tab_option]
</div>
<!-- /navbar -->

[next]

[cut:tab_option]
<a id="tab_button_[$index]" class="button active" href="javascript:changeTab([$index])"> [text] </a>
[/cut]

<!-- tab_content_[$index] -->
<div id="tab_content_[$index]" style="width:100%; padding:1rem">
<div class="[$scheme]-text-color">
<h3 class="[$scheme]-header-color header-font-family header-font-weight header-line-height h3-font-size">[text]</h3>
[text $content]
<table style="width:100%">
[list{ loop{ field; }} ]
</table>
</div>
</div>
<!-- /tab_content_[$index] -->

[next; loop{ if(!$last){]
[cut:tab_option]
<a id="tab_button_[$index]" class="button nav-background-color" href="javascript:changeTab([$index])"> [text] </a>
[/cut]
<!-- tab_content_[$index] -->
<div id="tab_content_[$index]" style="width:100%; padding:1rem; display:none; ">
<div class="[$scheme]-text-color [$scheme]-background-color [$scheme]-border-color [$scheme]-border-radius [$scheme]-padding [$scheme]-box-shadow" style="width:100%">
<h3 class="[$scheme]-header-color header-font-family header-font-weight header-line-height h3-font-size">[text]</h3>
[text $content]
<table style="width:100%">
[list{ loop{ field; }} ]
</table>
</div>
</div>
<!-- /tab_content_[$index] -->

[}else{]

[list { loop{]
<input type="hidden" id="[$name]" name="[$name]" value="[$value]">
[}}]
<input type="hidden" id="save" name="save" value="">
</form>
[}}]

<!-- layout_dialog_bottom -->
<div id="layout_dialog_bottom">
<div>
<button class="button-text-color button-background-color button-font-family button-font-weight button-font-size button-line-height button-border-radius" name="ok" id="ok" onclick="dialogOk()">[text:action_ok]</button>
<button class="button-text-color button-background-color button-font-family button-font-weight button-font-size button-line-height button-border-radius" name="restore" id="restore" onclick="dialogRestore()">[text:action_restore]</button>
<button class="button-text-color button-background-color button-font-family button-font-weight button-font-size button-line-height button-border-radius" name="cancel" id="cancel" onclick="dialogClose()">[text:action_cancel]</button>
</div>
</div>
<!-- /layout_dialog_bottom -->

[}}]
[cut:footerscript]
dialogStyleRefresh ();
[/cut]
'
'blocks'={
'fields/color_testable'='personaliteApearance_fieldsColor'
'fields/font_testable'='personaliteApearance_fieldsFont'
'fields/select_testable'='personaliteApearance_fieldsSelect'
}
