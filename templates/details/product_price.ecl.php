'local'={
'filters'='product'
}
'text'={
'caption'={
'pt'={
1='Preço (Adicionar ao carrinho)'
2=1
}
'en'={
1='Price (Add to cart)'
}
}
}
'html'='[scope (`cart`){]
<form action="[$url]" method="post">
<input type="hidden" name="id" value="[$id]">
<div>
[$document.currency_symbol][$product_price]
<input type="text" name="quantity" size="3" value="1" class="input">
<input type="submit" name="add" value="
[
if($bag){ text(`field_cart_add_bag`); }
elseif($order){ text(`field_cart_add_order`); }
else{  text(`field_cart_add`); }]">
</div>
</form>
[}]'
