'html'='<html lang="[$document.lang]">
<meta http-equiv="Content-Type" content="text/html; charset=[$document.charset]">
<META name="date" content="[$document.date]">
<meta name="generator" content="[$system.generator] v[$system.version]@[$system.release]">
<meta name="viewport"content="width=device-width, initial-scale=1.0">
<title>[text $document.title]</title>
<link rel="stylesheet" type="text/css" href="[shared:styles/ecolabore-basics.css]">
<script src="[shared:scripts/ecolabore-humperstilshen.js]"></script>
[paste:headerlinks]
[paste:style]
<body>

[mod(`title`){list{]
<h1>[text $title]</h1>
[}}]
[mod:panel main]

[paste:script]
</body>
</html>'
