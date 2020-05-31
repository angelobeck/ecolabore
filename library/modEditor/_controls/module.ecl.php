'html'='[cut:headerlinks]
<script src="[shared:scripts/ecolabore-editor.js]"></script>
[/cut]
[script]
EcolaboreEditor = new EcolaboreEditorClass ();

document.addEventListener ("keydown", EcolaboreEditor.eventKeyDown);
document.addEventListener ("keyup", EcolaboreEditor.eventKeyUp);

humperstilshen.refresh = function ()
{ // refresh
EcolaboreEditor.eventSubmit();
} // refresh
[/script]
<form id="wysiwyg" name="wysiwyg" action="[$url_submit]" method="post" charset="[$document.charset]">
</form>
[cut:editor_icon]
<a href="javascript:humperstilshen.dialogOpen(''editor'')" id="[$mod.name]_flag_green">
<span>
<img src="[shared:icons/editor/flag_green.svg]" alt="[text:navigation_editor_flag_green]">
</span>
</a>
[/cut]
[style]
.editor-empty-field { display:inline-block; width:1em; height:1em; background-image:url("[shared:icons/editor/pen.png]"); background-repeat:no-repeat; background-size:1em 1em; }
.editor-empty-field[]title]:after { display:inline-block; position:relative; padding-left:1em; content:"(" attr(title) ")"; opacity:.7; }
[/style]
'
