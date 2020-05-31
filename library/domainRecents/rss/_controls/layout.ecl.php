'html'='<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
<atom:link href="[$url_self]" rel="self" type="application/rss+xml" />
[scope(`home`){]
<title>[text $title]</title>
<link>[$url]</link>
<description>[text $description]</description>
[}]
<language>[$document.lang]</language>
<pubDate>[$pubDate]</pubDate>
[list{loop{]
<item>
<title>[text $title]</title>
<link>[$url]</link>
<guid>[$url]</guid>
<pubDate>[$pubDate]</pubDate>
[if($description){]
<description>[text $description]</description>
[}]
</item>
[}}]
</channel>
</rss>'
