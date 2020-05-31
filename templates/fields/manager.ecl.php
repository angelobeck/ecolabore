'text'={
'caption'={
'pt'={
1='Gerenciador de itens'
2=1
}
'en'={
1='Itens manager'
}
}
}
'html'='<div class="form-control[if($help){ ` form-control-help`; }]">
<label class="form-col-full-label" for="[$name]"[lang]>[text][if$required{`*`;}]</label>
[if($help){ `<div class="form-col-help">`; help; `</div>`; nl; }]
<div class="form-col-full-control">"
<select id="[$name]" name="[$name]" size="7" class="input" onkeypress="if(event.key == ''Delete''){ listManager.remove(''[$name]''); }else if (event.key == ''Enter''){ listManager.edit(''[$name]''); }" ondblclick="listManager.edit(''[$name]'')" style="width:100%">
[list{ loop{]
<option value="[$value]" data-url="[$url]" data-remove="[$url_remove]"[if($active){ ` selected`; }]>[text]</option>
[}}]
</select>
<input type="hidden" id="[$name]_serialized" name="[$name]_serialized" value="[$serialized]">
<div>

[if($move-enable){]
<a href="javascript:listManager.moveUp(''[$name]'')" role="button" aria-label="[text:action_move_up]">
<img src="[$document.url_icons]#nav_up_arrow" alt="[text:action_move_up]" class="big-icon">
</a>

<a href="javascript:listManager.moveDown(''[$name]'')" role="button" aria-label="[text:action_move_down]" >
<img src="[$document.url_icons]#nav_down_arrow" alt="[text:action_move_down]" class="big-icon">
</a>
[}if($url_add){]
<a href="javascript:listManager.add(''[$name]'', ''[$url_add]'')" role="button" aria-label="[text:action_add]">
<img src="[$document.url_icons]#action_add" alt="[text:action_add]" class="big-icon">
</a>
[}if($remove-enable){]
<a href="javascript:listManager.remove(''[$name]'')" role="button" aria-label="[text:action_remove]">
<img src="[$document.url_icons]#action_remove" alt="[text:action_remove]" class="big-icon">
</a>
[}if($edit-enable){]
<a href="javascript:listManager.edit(''[$name]'')" role="button" aria-label="[text:action_edit]">
<img src="[$document.url_icons]#action_configure" alt="[text:action_edit]" class="big-icon">
</a>
[}]
</div>
</div>
[cut:script manager]

function listManagerClass()
{ // listManagerClass
this.name = false;

this.moveUp = function (name)
{ // moveUp
this.name = name;
var select = document.getElementById (name);
if (select.selectedIndex > 0)
{ // can move
var index = select.selectedIndex;
var value = select.value;
var text = select.options[]index].text;
var url = select.options[]index].dataset.url;
select.options[]index] = select.options[]index - 1];
var option     = document.createElement("option");
option.setAttribute ("value", value);
option.text = text;
option.dataset.url = url;
select.add (option, index - 1);
select.selectedIndex = index - 1;

this.serialize();
} // can move
} // moveUp

this.moveDown = function (name)
{ // moveDown
this.name = name;
var select = document.getElementById (name);
if (select.selectedIndex >= 0 && select.selectedIndex < select.length - 1)
{ // can move
var index = select.selectedIndex;
var value = select.value;
var text = select.options[]index].text;
var url = select.options[]index].dataset.url;
select.options[]index] = select.options[]index + 1];
var option     = document.createElement("option");
option.setAttribute ("value", value);
option.text = text;
option.dataset.url = url;
select.add (option, index + 1);
select.selectedIndex = index + 1;

this.serialize();
} // can move
} // moveDown

this.add = function (name, url)
{ // add
this.name = name;

var left = (screen.width) ? (screen.width-400)/2 : 200;
var top = (screen.height) ? (screen.height-600)/2 : 25;
window.open (url, "", "width=400,height=600,top=" + top + ",left=" + left);
} // add

this.remove = function (name)
{ // remove
this.name = name;
var select = document.getElementById (name);
if (select.selectedIndex >= 0)
{ // can remove
var index = select.selectedIndex;
if (select.options[]index].dataset.remove && select.options[]index].dataset.remove.length)
{ // remove request
var request = new XMLHttpRequest();
request.mySelect = select;
request.myIndex = index;
request.myName = name;
request.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
alert (this.responseText);
var select = this.mySelect;
var index = this.myIndex;
var name = this.myName;
select.remove (index);
if (select.length)
{ // focus new item
if (index < select.length)
select.selectedIndex = index;
else
select.selectedIndex = select.length - 1;
} // focus new item
listManager.name = name;
listManager.serialize();

}
}
request.open(select.options[]index].dataset.remove, true);
request.send(); 
return;
} // remove request
select.remove (index);
if (select.length)
{ // focus new item
if (index < select.length)
select.selectedIndex = index;
else
select.selectedIndex = select.length - 1;
} // focus new item
this.serialize();
} // can remove
} // remove

this.edit = function (name)
{ // edit
this.name = name;
var select = document.getElementById (name);
if (select.selectedIndex >= 0)
{ // selected
var url = select.options[]select.selectedIndex].dataset.url;
if (!url || !url.length)
return;

var left = (screen.width) ? (screen.width-400)/2 : 200;
var top = (screen.height) ? (screen.height-600)/2 : 25;
window.open (url, "", "width=400,height=600,top=" + top + ",left=" + left);
} // selected
} // edit

this.append = function (value, text, url="")
{ // append
if(!this.name)
return;

var select = document.getElementById (this.name);
var option     = document.createElement("option");
option.setAttribute ("value", value);
option.text = text;
option.dataset.url = url;
select.add (option);

select.selectedIndex = select.length - 1;
select.focus();
this.serialize();
} // append

this.update = function (oldValue, newValue, newText, newUrl)
{ // update
if(!this.name)
return;

var select = document.getElementById (this.name);
for (var index in select.options)
{ // each option
if (select.options[]index].value == oldValue)
{ // found
if (newValue.length)
{ // update
var option = select.options[]index];
option.value = newValue;
option.text = newText;
option.dataset.url = newUrl;
select.selectedIndex = index;
select.focus();
this.serialize();
return;
} // update
else
{ // remove
select.remove (index);
this.serialize();
return;
} // remove
} // found
} // each option

this.append (newValue, newText, newUrl);
} // update

this.serialize = function ()
{ // serialize
var buffer = "";
var select = document.getElementById (this.name);
for (var i = 0; i < select.length; i++)
{ // each element
buffer += select.options[]i].value + " ";
} // each element
document.getElementById (this.name + "_serialized").value = buffer;
} // serialize

} // listManagerClass

listManager = new listManagerClass();
[/cut]
'
