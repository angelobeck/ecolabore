'flags'={
'modLayout_base'='responsive'
'modLayout_from'='templates'
'modLayout_name'='dialog_tabs'
}
'local'={
'scheme'='system'
}
'html'='[script]

// changes dialog tab
tabOld = 2;

function changeTab(){
var tabIndex = 2 + document.controls.select_tab.selectedIndex;
document.getElementById(''tab_content_''+tabOld).style.display = ''none'';
document.getElementById(''tab_content_''+tabIndex).style.display = ''block'';
tabOld = tabIndex;
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

document.controls.select_tab.focus();
[/script]
[style]
/* Panels positioning scheme*/
#layout_dialog_tabs { position:fixed; display:block; top:4rem; right:auto; bottom:4rem; left:0; width:25vw; padding:.4rem; }
  #layout_dialog_tabs select { width:100%; }
fieldset { position:fixed; display:block; top:4rem; right:0; bottom:4rem; left:25vw; padding:.4rem; }
.scrollable { overflow:auto; }
#layout_dialog_bottom { position:fixed; display:flex; top:auto; right:0; bottom:0; left:0; height:4rem; }
#layout_dialog_bottom > :first-child { margin:auto; }

[/style]

[mod(`formulary`){ list{]
<form id="controls" name="controls" action="[$url]" method="post" enctype="multipart/form-data" charset="[$document.charset]">

<!-- layout_dialog_tabs -->
<div id="layout_dialog_tabs">
<label for="select_tab">[text:layout_tabs]</label><br >
<select id="select_tab" size="6" onchange="changeTab()">
[paste:tab_option]
</select>
</div>
<!-- /layout_dialog_tabs -->

<!-- layout_dialog_main -->
<div id="layout_dialog_main">
[next]

[cut:tab_option]
<option value="[$index]" selected> [text] </option>
[/cut]
<!-- tab_content_[$index] -->
<fieldset id="tab_content_[$index]" class="tab_content">
<legend>[text]</legend>
<div class="scrollable">
<div class="form-layout-grid">
[list{ loop{ field; }} ]
</div>
</div>
</fieldset>
<!-- /tab_content_[$index] -->

[next; loop{ if(!$last){]
[cut:tab_option]
<option value="[$index]"> [text] </option>
[/cut]
<!-- tab_content_[$index] -->
<fieldset id="tab_content_[$index]" class="tab_content" style="display:none">
<legend>[text]</legend>
<div class="scrollable">
<div class="form-layout-grid">
[list{ loop{ field; }} ]
</div>
</div>
</fieldset>
<!-- /tab_content_[$index] -->

[}else{]
</div>
<!-- /layout_dialog_main -->

[list { loop{]
<input type="hidden" id="[$name]" name="[$name]" value="[$value]">
[}}]
<input type="hidden" id="save" name="save" value="">
</form>
[}}]

<!-- layout_dialog_bottom -->
<div id="layout_dialog_bottom">
<div>
<button name="ok" id="ok" onclick="dialogOk()">[text:action_ok]</button>
[if($document.remove_object){])
<button name="restore" id="restore" onclick="dialogRestore()">[text:action_remove_object]</button>
[}else{]
<button name="restore" id="restore" onclick="dialogRestore()">[text:action_restore]</button>
[}]

<button name="cancel" id="cancel" onclick="dialogClose()">[text:action_cancel]</button>
</div>
</div>
<!-- /layout_dialog_bottom -->

[}}]
'
