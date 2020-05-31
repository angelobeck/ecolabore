'text'={
'caption'={
'pt'={
1='ecolabore.css'
2=1
}
'en'={
1='ecolabore.css'
}
}
}
'html'='

/* system defaults */
* { box-sizing:border-box; }
html { -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; }
body { font-size:100%; margin:0; padding:0; min-height:100%; width:100%; }
.sr-only { position:absolute; width:1px; height:1px; padding:0; margin:-1px; overflow:hidden; clip:rect(0,0,0,0); border:0; }
h1, h2, h3, h4, h5, h6, hr { clear:both; }
div, dl, dt, dd, ul, ol, li, h1, h2, h3, h4, h5, h6, pre, form, p, blockquote, th, td { margin:0; padding:0;  }
ul { list-style-type:disc; }
button + button, input + button, input + input { margin-left:.5em; }
.center { display:inline-block; width:100%; text-align:center; } 
.center > * { display:inline-block; }
hr { max-width:75%; height:0; margin:1.25rem auto; border-top:0; border-right:0; border-bottom:1px solid; border-left:0;  }

/* Zurb lists */
ul, ol, dl { margin-bottom:1rem; list-style-position:outside; line-height:1.6;  }
li { font-size:inherit;  }
ul { margin-left:1.25rem; list-style-type:disc;  }
ol { margin-left:1.25rem;  }
ul ul, ol ul, ul ol, ol ol { margin-left:1.25rem; margin-bottom:0;  }
ul.no-bullet, ol.no-bullet { margin-left:0; list-style:none;  }
dl { margin-bottom:1rem;  }
dl dt { margin-bottom:0.3rem; font-weight:bold;  }
figure { margin:0;  }

/* Lists */
ul.alphabetic { list-style-type:lower-latin; }
li > p:first-child { display:inline; }

/* Fonts and paragraphs*/
[paste:font-face]
body { font-family:[font_stack$paragraph-font-name]; font-size:[$paragraph-font-size]; letter-spacing:[$paragraph-letter-spacing]; text-shadow:[$paragraph-text-shadow];  }
p { text-align:[$paragraph-text-align]; line-height:[$paragraph-line-height]; margin-bottom:[$paragraph-margin-bottom]; margin-left:[$paragraph-margin-left]; text-indent:[$paragraph-text-indent]; }
label, .label { font-family:[font_stack$label-font-name]; font-size:[$label-font-size]; font-weight:[$label-font-weight]; letter-spacing:[$label-letter-spacing]; text-shadow:[$label-text-shadow];  text-align:[$label-text-align]; line-height:[$label-line-height]; margin-bottom:[$label-margin-bottom]; margin-left:[$label-margin-left]; text-indent:[$label-text-indent]; }
blockquote { font-family:[font_stack$blockquote-font-name]; font-size:[$blockquote-font-size]; letter-spacing:[$blockquote-letter-spacing]; text-shadow:[$blockquote-text-shadow];  text-align:[$blockquote-text-align]; }
blockquote p { line-height:[$blockquote-line-height]; margin-bottom:[$blockquote-margin-bottom]; margin-left:[$blockquote-margin-left]; text-indent:[$blockquote-text-indent]; }
legend, .legend { font-family:[font_stack$legend-font-name]; font-size:[$legend-font-size]; font-weight:[$legend-font-weight]; letter-spacing:[$legend-letter-spacing]; text-shadow:[$legend-text-shadow];  text-align:[$legend-text-align]; line-height:[$legend-line-height]; margin-bottom:[$legend-margin-bottom]; margin-left:[$legend-margin-left]; text-indent:[$legend-text-indent]; }
a, .a { font-family:[font_stack$link-font-name]; font-size:[$link-font-size]; font-weight:[$link-font-weight]; letter-spacing:[$link-letter-spacing]; text-shadow:[$link-text-shadow];  }
h1, h2, h3, h4, h5, h6 { font-family:[font_stack$header-font-name]; font-weight:[$header-font-weight]; letter-spacing:[$header-letter-spacing]; text-shadow:[$header-text-shadow];  text-align:[$header-text-align]; line-height:[$header-line-height]; margin-bottom:[$header-margin-bottom]; margin-left:[$header-margin-left]; text-indent:[$header-text-indent]; }
.caption { font-family:[font_stack$caption-font-name]; font-size:[$caption-font-size]; font-weight:[$caption-font-weight]; letter-spacing:[$caption-letter-spacing]; text-shadow:[$caption-text-shadow];  text-align:[$caption-text-align]; line-height:[$caption-line-height]; margin-bottom:[$caption-margin-bottom]; margin-left:[$caption-margin-left]; text-indent:[$caption-text-indent]; }
.bar { font-family:[font_stack$bar-font-name]; font-size:[$bar-font-size]; font-weight:[$bar-font-weight]; letter-spacing:[$bar-letter-spacing]; text-shadow:[$bar-text-shadow];  text-align:[$bar-text-align]; line-height:[$bar-line-height]; margin-bottom:[$bar-margin-bottom]; margin-left:[$bar-margin-left]; text-indent:[$bar-text-indent]; }
pre, code { font-family:[font_stack$pre-font-name]; font-size:[$pre-font-size]; font-weight:[$pre-font-weight]; letter-spacing:[$pre-letter-spacing]; text-shadow:[$pre-text-shadow];  text-align:[$pre-text-align]; line-height:[$pre-line-height]; margin-bottom:[$pre-margin-bottom]; margin-left:[$pre-margin-left]; text-indent:[$pre-text-indent]; }

h1 { font-size:[$h1-xl-size]; }
h2 { font-size:[$h2-xl-size]; }
h3 { font-size:[$h3-xl-size]; }
h4 { font-size:[$h4-xl-size]; }
h5 { font-size:[$h5-xl-size]; }
h6 { font-size:[$h6-xl-size]; }

em, i { font-style:italic; line-height:inherit;  }
strong, b { font-weight:bold; line-height:inherit;  }
small { font-size:80%; line-height:inherit;  }
ins { text-decoration:none; font-weight:bold; }
del { text-decoration:line-through; }
u { text-decoration:underline; }

/* Schemes */
body { color:[$document-text-color]; background-color:[$document-background-color]; border-color:[$document-border-color]; }
h1, h2, h3, h4, h5, h6 { color:[$document-header-color]; }
.caption { color:[$document-caption-color]; }
a, a:visited, .a { color:[$document-link-color]; }
a:hover, a:focus, a:active, .active { color:[$document-link-active-color]; }
button, .button { font-family:[font_stack$document-button-font-name]; font-weight:[$document-button-font-weight]; font-size:[$document-button-font-size]; letter-spacing:[$document-button-letter-spacing]; border-radius:[$document-button-border-radius]; line-height:[$document-button-line-height]; padding:[$document-button-vertical-padding] [$document-button-horizontal-padding]; color:[$document-button-color]; background-color:[$document-button-background-color]; text-shadow:[$document-button-text-shadow]; border:[$document-button-border-width] [$document-button-border-style] [$document-button-border-color]; box-shadow:[$document-button-box-shadow]; transition:[$document-button-transition]; }
button:hover, button:focus, button:active, button.active, .button:hover, .button:focus, .button:active, .button.active { color:[$document-button-active-color]; background-color:[$document-button-active-background-color]; text-shadow:[$document-button-active-text-shadow]; border:[$document-button-active-border-width] [$document-button-active-border-style] [$document-button-active-border-color]; box-shadow:[$document-button-active-box-shadow]; }
.input { font-family:[font_stack$document-input-font-name]; font-weight:[$document-input-font-weight]; font-size:[$document-input-font-size]; letter-spacing:[$document-input-letter-spacing]; border-radius:[$document-input-border-radius]; line-height:[$document-input-line-height]; padding:[$document-input-vertical-padding] [$document-input-horizontal-padding]; color:[$document-input-color]; background-color:[$document-input-background-color]; text-shadow:[$document-input-text-shadow]; border:[$document-input-border-width] [$document-input-border-style] [$document-input-border-color]; box-shadow:[$document-input-box-shadow]; transition:[$document-input-transition]; }
.input:hover, .input:focus { color:[$document-input-active-color]; background-color:[$document-input-active-background-color]; text-shadow:[$document-input-active-text-shadow]; border:[$document-input-active-border-width] [$document-input-active-border-style] [$document-input-active-border-color]; box-shadow:[$document-input-active-box-shadow]; }
.img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$document-background-color], 0 0 0 3px [$document-background-color]; }

.system { color:[$system-text-color]; background-color:[$system-background-color]; border-color:[$system-border-color]; padding:[$system-box-padding]; border-radius:[$system-box-border-radius]; box-shadow:[$system-box-shadow]; }
.system h1, .system h2, .system h3, .system h4, .system h5, .system h6 { color:[$system-header-color]; }
.system .caption { color:[$system-caption-color]; }
.system a, .system a:visited, .system .a { color:[$system-link-color]; }
.system a:hover, .system a:focus, .system a:active, .system .active { color:[$system-link-active-color]; }
.system button, .system .button { font-family:[font_stack$system-button-font-name]; font-weight:[$system-button-font-weight]; font-size:[$system-button-font-size]; letter-spacing:[$system-button-letter-spacing]; border-radius:[$system-button-border-radius]; line-height:[$system-button-line-height]; padding:[$system-button-vertical-padding] [$system-button-horizontal-padding]; color:[$system-button-color]; background-color:[$system-button-background-color]; text-shadow:[$system-button-text-shadow]; border:[$system-button-border-width] [$system-button-border-style] [$system-button-border-color]; box-shadow:[$system-button-box-shadow]; transition:[$system-button-transition]; }
.system button:hover, .system button:focus, .system button:active, .system button.active, .system .button:hover, .system .button:focus, .system .button:active, .system .button.active { color:[$system-button-active-color]; background-color:[$system-button-active-background-color]; text-shadow:[$system-button-active-text-shadow]; border:[$system-button-active-border-width] [$system-button-active-border-style] [$system-button-active-border-color]; box-shadow:[$system-button-active-box-shadow]; }
.system .input { font-family:[font_stack$system-input-font-name]; font-weight:[$system-input-font-weight]; font-size:[$system-input-font-size]; letter-spacing:[$system-input-letter-spacing]; border-radius:[$system-input-border-radius]; line-height:[$system-input-line-height]; padding:[$system-input-vertical-padding] [$system-input-horizontal-padding]; color:[$system-input-color]; background-color:[$system-input-background-color]; text-shadow:[$system-input-text-shadow]; border:[$system-input-border-width] [$system-input-border-style] [$system-input-border-color]; box-shadow:[$system-input-box-shadow]; transition:[$system-input-transition]; }
.system .input:hover, .system .input:focus { color:[$system-input-active-color]; background-color:[$system-input-active-background-color]; text-shadow:[$system-input-active-text-shadow]; border:[$system-input-active-border-width] [$system-input-active-border-style] [$system-input-active-border-color]; box-shadow:[$system-input-active-box-shadow]; }
.system .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$system-background-color], 0 0 0 3px [$system-background-color]; }

.panel { color:[$panel-text-color]; background-color:[$panel-background-color]; border-color:[$panel-border-color]; padding:[$panel-box-padding]; border-radius:[$panel-box-border-radius]; box-shadow:[$panel-box-shadow]; }
.panel h1, .panel h2, .panel h3, .panel h4, .panel h5, .panel h6 { color:[$panel-header-color]; }
.panel .caption { color:[$panel-caption-color]; }
.panel a, .panel a:visited, .panel .a { color:[$panel-link-color]; }
.panel a:hover, .panel a:focus, .panel a:active, .panel .active { color:[$panel-link-active-color]; }
.panel button, .panel .button { font-family:[font_stack$panel-button-font-name]; font-weight:[$panel-button-font-weight]; font-size:[$panel-button-font-size]; letter-spacing:[$panel-button-letter-spacing]; border-radius:[$panel-button-border-radius]; line-height:[$panel-button-line-height]; padding:[$panel-button-vertical-padding] [$panel-button-horizontal-padding]; color:[$panel-button-color]; background-color:[$panel-button-background-color]; text-shadow:[$panel-button-text-shadow]; border:[$panel-button-border-width] [$panel-button-border-style] [$panel-button-border-color]; box-shadow:[$panel-button-box-shadow]; transition:[$panel-button-transition]; }
.panel button:hover, .panel button:focus, .panel button:active, .panel button.active, .panel .button:hover, .panel .button:focus, .panel .button:active, .panel .button.active { color:[$panel-button-active-color]; background-color:[$panel-button-active-background-color]; text-shadow:[$panel-button-active-text-shadow]; border:[$panel-button-active-border-width] [$panel-button-active-border-style] [$panel-button-active-border-color]; box-shadow:[$panel-button-active-box-shadow]; }
.panel .input { font-family:[font_stack$panel-input-font-name]; font-weight:[$panel-input-font-weight]; font-size:[$panel-input-font-size]; letter-spacing:[$panel-input-letter-spacing]; border-radius:[$panel-input-border-radius]; line-height:[$panel-input-line-height]; padding:[$panel-input-vertical-padding] [$panel-input-horizontal-padding]; color:[$panel-input-color]; background-color:[$panel-input-background-color]; text-shadow:[$panel-input-text-shadow]; border:[$panel-input-border-width] [$panel-input-border-style] [$panel-input-border-color]; box-shadow:[$panel-input-box-shadow]; transition:[$panel-input-transition]; }
.panel .input:hover, .panel .input:focus { color:[$panel-input-active-color]; background-color:[$panel-input-active-background-color]; text-shadow:[$panel-input-active-text-shadow]; border:[$panel-input-active-border-width] [$panel-input-active-border-style] [$panel-input-active-border-color]; box-shadow:[$panel-input-active-box-shadow]; }
.panel .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$panel-background-color], 0 0 0 3px [$panel-background-color]; }

.nav { color:[$nav-text-color]; background-color:[$nav-background-color]; border-color:[$nav-border-color]; padding:[$nav-box-padding]; border-radius:[$nav-box-border-radius]; box-shadow:[$nav-box-shadow]; }
.nav h1, .nav h2, .nav h3, .nav h4, .nav h5, .nav h6 { color:[$nav-header-color]; }
.nav .caption { color:[$nav-caption-color]; }
.nav a, .nav a:visited, .nav .a { color:[$nav-link-color]; }
.nav a:hover, .nav a:focus, .nav a:active, .nav .active { color:[$nav-link-active-color]; }
.nav button, .nav .button { font-family:[font_stack$nav-button-font-name]; font-weight:[$nav-button-font-weight]; font-size:[$nav-button-font-size]; letter-spacing:[$nav-button-letter-spacing]; border-radius:[$nav-button-border-radius]; line-height:[$nav-button-line-height]; padding:[$nav-button-vertical-padding] [$nav-button-horizontal-padding]; color:[$nav-button-color]; background-color:[$nav-button-background-color]; text-shadow:[$nav-button-text-shadow]; border:[$nav-button-border-width] [$nav-button-border-style] [$nav-button-border-color]; box-shadow:[$nav-button-box-shadow]; transition:[$nav-button-transition]; }
.nav button:hover, .nav button:focus, .nav button:active, .nav button.active, .nav .button:hover, .nav .button:focus, .nav .button:active, .nav .button.active { color:[$nav-button-active-color]; background-color:[$nav-button-active-background-color]; text-shadow:[$nav-button-active-text-shadow]; border:[$nav-button-active-border-width] [$nav-button-active-border-style] [$nav-button-active-border-color]; box-shadow:[$nav-button-active-box-shadow]; }
.nav .input { font-family:[font_stack$nav-input-font-name]; font-weight:[$nav-input-font-weight]; font-size:[$nav-input-font-size]; letter-spacing:[$nav-input-letter-spacing]; border-radius:[$nav-input-border-radius]; line-height:[$nav-input-line-height]; padding:[$nav-input-vertical-padding] [$nav-input-horizontal-padding]; color:[$nav-input-color]; background-color:[$nav-input-background-color]; text-shadow:[$nav-input-text-shadow]; border:[$nav-input-border-width] [$nav-input-border-style] [$nav-input-border-color]; box-shadow:[$nav-input-box-shadow]; transition:[$nav-input-transition]; }
.nav .input:hover, .nav .input:focus { color:[$nav-input-active-color]; background-color:[$nav-input-active-background-color]; text-shadow:[$nav-input-active-text-shadow]; border:[$nav-input-active-border-width] [$nav-input-active-border-style] [$nav-input-active-border-color]; box-shadow:[$nav-input-active-box-shadow]; }
.nav .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$nav-background-color], 0 0 0 3px [$nav-background-color]; }

.spotlight { color:[$spotlight-text-color]; background-color:[$spotlight-background-color]; border-color:[$spotlight-border-color]; padding:[$spotlight-box-padding]; border-radius:[$spotlight-box-border-radius]; box-shadow:[$spotlight-box-shadow]; }
.spotlight h1, .spotlight h2, .spotlight h3, .spotlight h4, .spotlight h5, .spotlight h6 { color:[$spotlight-header-color]; }
.spotlight .caption { color:[$spotlight-caption-color]; }
.spotlight a, .spotlight a:visited, .spotlight .a { color:[$spotlight-link-color]; }
.spotlight a:hover, .spotlight a:focus, .spotlight a:active, .spotlight .active { color:[$spotlight-link-active-color]; }
.spotlight button, .spotlight .button { font-family:[font_stack$spotlight-button-font-name]; font-weight:[$spotlight-button-font-weight]; font-size:[$spotlight-button-font-size]; letter-spacing:[$spotlight-button-letter-spacing]; border-radius:[$spotlight-button-border-radius]; line-height:[$spotlight-button-line-height]; padding:[$spotlight-button-vertical-padding] [$spotlight-button-horizontal-padding]; color:[$spotlight-button-color]; background-color:[$spotlight-button-background-color]; text-shadow:[$spotlight-button-text-shadow]; border:[$spotlight-button-border-width] [$spotlight-button-border-style] [$spotlight-button-border-color]; box-shadow:[$spotlight-button-box-shadow]; transition:[$spotlight-button-transition]; }
.spotlight button:hover, .spotlight button:focus, .spotlight button:active, .spotlight button.active, .spotlight .button:hover, .spotlight .button:focus, .spotlight .button:active, .spotlight .button.active { color:[$spotlight-button-active-color]; background-color:[$spotlight-button-active-background-color]; text-shadow:[$spotlight-button-active-text-shadow]; border:[$spotlight-button-active-border-width] [$spotlight-button-active-border-style] [$spotlight-button-active-border-color]; box-shadow:[$spotlight-button-active-box-shadow]; }
.spotlight .input { font-family:[font_stack$spotlight-input-font-name]; font-weight:[$spotlight-input-font-weight]; font-size:[$spotlight-input-font-size]; letter-spacing:[$spotlight-input-letter-spacing]; border-radius:[$spotlight-input-border-radius]; line-height:[$spotlight-input-line-height]; padding:[$spotlight-input-vertical-padding] [$spotlight-input-horizontal-padding]; color:[$spotlight-input-color]; background-color:[$spotlight-input-background-color]; text-shadow:[$spotlight-input-text-shadow]; border:[$spotlight-input-border-width] [$spotlight-input-border-style] [$spotlight-input-border-color]; box-shadow:[$spotlight-input-box-shadow]; transition:[$spotlight-input-transition]; }
.spotlight .input:hover, .spotlight .input:focus { color:[$spotlight-input-active-color]; background-color:[$spotlight-input-active-background-color]; text-shadow:[$spotlight-input-active-text-shadow]; border:[$spotlight-input-active-border-width] [$spotlight-input-active-border-style] [$spotlight-input-active-border-color]; box-shadow:[$spotlight-input-active-box-shadow]; }
.spotlight .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$spotlight-background-color], 0 0 0 3px [$spotlight-background-color]; }

.formulary { color:[$formulary-text-color]; background-color:[$formulary-background-color]; border-color:[$formulary-border-color]; padding:[$formulary-box-padding]; border-radius:[$formulary-box-border-radius]; box-shadow:[$formulary-box-shadow]; }
.formulary h1, .formulary h2, .formulary h3, .formulary h4, .formulary h5, .formulary h6 { color:[$formulary-header-color]; }
.formulary .caption { color:[$formulary-caption-color]; }
.formulary a, .formulary a:visited, .formulary .a { color:[$formulary-link-color]; }
.formulary a:hover, .formulary a:focus, .formulary a:active, .formulary .active { color:[$formulary-link-active-color]; }
.formulary button, .formulary .button { font-family:[font_stack$formulary-button-font-name]; font-weight:[$formulary-button-font-weight]; font-size:[$formulary-button-font-size]; letter-spacing:[$formulary-button-letter-spacing]; border-radius:[$formulary-button-border-radius]; line-height:[$formulary-button-line-height]; padding:[$formulary-button-vertical-padding] [$formulary-button-horizontal-padding]; color:[$formulary-button-color]; background-color:[$formulary-button-background-color]; text-shadow:[$formulary-button-text-shadow]; border:[$formulary-button-border-width] [$formulary-button-border-style] [$formulary-button-border-color]; box-shadow:[$formulary-button-box-shadow]; transition:[$formulary-button-transition]; }
.formulary button:hover, .formulary button:focus, .formulary button:active, .formulary button.active, .formulary .button:hover, .formulary .button:focus, .formulary .button:active, .formulary .button.active { color:[$formulary-button-active-color]; background-color:[$formulary-button-active-background-color]; text-shadow:[$formulary-button-active-text-shadow]; border:[$formulary-button-active-border-width] [$formulary-button-active-border-style] [$formulary-button-active-border-color]; box-shadow:[$formulary-button-active-box-shadow]; }
.formulary .input { font-family:[font_stack$formulary-input-font-name]; font-weight:[$formulary-input-font-weight]; font-size:[$formulary-input-font-size]; letter-spacing:[$formulary-input-letter-spacing]; border-radius:[$formulary-input-border-radius]; line-height:[$formulary-input-line-height]; padding:[$formulary-input-vertical-padding] [$formulary-input-horizontal-padding]; color:[$formulary-input-color]; background-color:[$formulary-input-background-color]; text-shadow:[$formulary-input-text-shadow]; border:[$formulary-input-border-width] [$formulary-input-border-style] [$formulary-input-border-color]; box-shadow:[$formulary-input-box-shadow]; transition:[$formulary-input-transition]; }
.formulary .input:hover, .formulary .input:focus { color:[$formulary-input-active-color]; background-color:[$formulary-input-active-background-color]; text-shadow:[$formulary-input-active-text-shadow]; border:[$formulary-input-active-border-width] [$formulary-input-active-border-style] [$formulary-input-active-border-color]; box-shadow:[$formulary-input-active-box-shadow]; }
.formulary .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$formulary-background-color], 0 0 0 3px [$formulary-background-color]; }

.card { color:[$card-text-color]; background-color:[$card-background-color]; border-color:[$card-border-color]; padding:[$card-box-padding]; border-radius:[$card-box-border-radius]; box-shadow:[$card-box-shadow]; }
.card h1, .card h2, .card h3, .card h4, .card h5, .card h6 { color:[$card-header-color]; }
.card .caption { color:[$card-caption-color]; }
.card a, .card a:visited, .card .a { color:[$card-link-color]; }
.card a:hover, .card a:focus, .card a:active, .card .active { color:[$card-link-active-color]; }
.card button, .card .button { font-family:[font_stack$card-button-font-name]; font-weight:[$card-button-font-weight]; font-size:[$card-button-font-size]; letter-spacing:[$card-button-letter-spacing]; border-radius:[$card-button-border-radius]; line-height:[$card-button-line-height]; padding:[$card-button-vertical-padding] [$card-button-horizontal-padding]; color:[$card-button-color]; background-color:[$card-button-background-color]; text-shadow:[$card-button-text-shadow]; border:[$card-button-border-width] [$card-button-border-style] [$card-button-border-color]; box-shadow:[$card-button-box-shadow]; transition:[$card-button-transition]; }
.card button:hover, .card button:focus, .card button:active, .card button.active, .card .button:hover, .card .button:focus, .card .button:active, .card .button.active { color:[$card-button-active-color]; background-color:[$card-button-active-background-color]; text-shadow:[$card-button-active-text-shadow]; border:[$card-button-active-border-width] [$card-button-active-border-style] [$card-button-active-border-color]; box-shadow:[$card-button-active-box-shadow]; }
.card .input { font-family:[font_stack$card-input-font-name]; font-weight:[$card-input-font-weight]; font-size:[$card-input-font-size]; letter-spacing:[$card-input-letter-spacing]; border-radius:[$card-input-border-radius]; line-height:[$card-input-line-height]; padding:[$card-input-vertical-padding] [$card-input-horizontal-padding]; color:[$card-input-color]; background-color:[$card-input-background-color]; text-shadow:[$card-input-text-shadow]; border:[$card-input-border-width] [$card-input-border-style] [$card-input-border-color]; box-shadow:[$card-input-box-shadow]; transition:[$card-input-transition]; }
.card .input:hover, .card .input:focus { color:[$card-input-active-color]; background-color:[$card-input-active-background-color]; text-shadow:[$card-input-active-text-shadow]; border:[$card-input-active-border-width] [$card-input-active-border-style] [$card-input-active-border-color]; box-shadow:[$card-input-active-box-shadow]; }
.card .img-smoke:before { box-shadow:inset 0 0 [$img-smoke-width] [$img-smoke-width] [$card-background-color], 0 0 0 3px [$card-background-color]; }


/* Default positioning scheme */
.mod { display:block; position:relative; width:100%; padding-top:[$mod-padding-top]; padding-right:[$mod-padding-right]; padding-bottom:[$mod-padding-bottom]; padding-left:[$mod-padding-left]; }
.box { display:block; width:100%; }
.list { display:block; width:100%; }
.details { width:100%; text-align:left; }
.alignment { display:inline-block; vertical-align:top; width:100%; text-align:inherit; }

.mod .position-left, .mod .float-left { padding-left:0; }
.mod .position-center { padding-left:0; padding-right:0; }
.mod .position-right, .mod .position-float-right { padding-right:0; }

/* Positioning */
.position-inline { display:inline-block; vertical-align:top; }
.position-left { margin-left:0; margin-right:0; }
.position-center { margin-left:auto; margin-right:auto; }
.position-right { margin-left:auto; margin-right:0; }
.position-float-left { float:left; }
.position-float-right { float:right; }

.wd-sm-auto, .wd-sm-auto > .box, .wd-sm-auto > .box > .list { width:auto; }

.wd-sm-1 { width:8.1%; }
.wd-sm-2 { width:16.4%; }
.wd-sm-3 { width:24.7%; }
.wd-sm-4 { width:33.1%; }
.wd-sm-5 { width:41.4%; }
.wd-sm-6 { width:48.7%; }
.wd-sm-7 { width:58.1%; }
.wd-sm-8 { width:66.4%; }
.wd-sm-9 { width:74.7%; }
.wd-sm-10 { width:83.1%; }
.wd-sm-11 { width:91.4%; }
.wd-sm-12 { width:100%; }

.position-float-left.wd-sm-1, .position-float-right.wd-sm-1 { width:8.1vw; }
.position-float-left.wd-sm-2, .position-float-right.wd-sm-2 { width:16.4vw; }
.position-float-left.wd-sm-3, .position-float-right.wd-sm-3 { width:24.7vw; }
.position-float-left.wd-sm-4, .position-float-right.wd-sm-4 { width:33.1vw; }
.position-float-left.wd-sm-5, .position-float-right.wd-sm-5 { width:41.4vw; }
.position-float-left.wd-sm-6, .position-float-right.wd-sm-6 { width:48.7vw; }
.position-float-left.wd-sm-7, .position-float-right.wd-sm-7 { width:58.1vw; }
.position-float-left.wd-sm-8, .position-float-right.wd-sm-8 { width:66.4vw; }
.position-float-left.wd-sm-9, .position-float-right.wd-sm-9 { width:74.7vw; }

.position-float-left.wd-sm-10, .position-float-left.wd-sm-11, .position-float-left.wd-sm-12 {  float:none; margin-left:0; margin-right:auto; } 
.position-float-right.wd-sm-10, .position-float-right.wd-sm-11, .position-float-right.wd-sm-12 {  float:none; margin-left:auto; margin-right:0; } 

@media (min-width:[$media-md-min]) {
.wd-md-1 { width:8.1%; }
.wd-md-2 { width:16.4%; }
.wd-md-3 { width:24.7%; }
.wd-md-4 { width:33.1%; }
.wd-md-5 { width:41.4%; }
.wd-md-6 { width:48.7%; }
.wd-md-7 { width:58.1%; }
.wd-md-8 { width:66.4%; }
.wd-md-9 { width:74.7%; }
.wd-md-10 { width:83.1%; }
.wd-md-11 { width:91.4%; }
.wd-md-12 { width:100%; }

.position-float-left.wd-md-1, .position-float-right.wd-md-1 { width:8.1vw; }
.position-float-left.wd-md-2, .position-float-right.wd-md-2 { width:16.4vw; }
.position-float-left.wd-md-3, .position-float-right.wd-md-3 { width:24.7vw; }
.position-float-left.wd-md-4, .position-float-right.wd-md-4 { width:33.1vw; }
.position-float-left.wd-md-5, .position-float-right.wd-md-5 { width:41.4vw; }
.position-float-left.wd-md-6, .position-float-right.wd-md-6 { width:48.7vw; }
.position-float-left.wd-md-7, .position-float-right.wd-md-7 { width:58.1vw; }
.position-float-left.wd-md-8, .position-float-right.wd-md-8 { width:66.4vw; }
.position-float-left.wd-md-9, .position-float-right.wd-md-9 { width:74.7vw; }

.position-float-left.wd-md-10, .position-float-left.wd-md-11, .position-float-left.wd-md-12 {  float:none; margin-left:0; margin-right:auto; } 
.position-float-right.wd-md-10, .position-float-right.wd-md-11, .position-float-right.wd-md-12 {  float:none; margin-left:auto; margin-right:0; } 
}

@media (min-width:[$media-lg-min]) {
.wd-lg-1 { width:8.1%; }
.wd-lg-2 { width:16.4%; }
.wd-lg-3 { width:24.7%; }
.wd-lg-4 { width:33.1%; }
.wd-lg-5 { width:41.4%; }
.wd-lg-6 { width:48.7%; }
.wd-lg-7 { width:58.1%; }
.wd-lg-8 { width:66.4%; }
.wd-lg-9 { width:74.7%; }
.wd-lg-10 { width:83.1%; }
.wd-lg-11 { width:91.4%; }
.wd-lg-12 { width:100%; }

.position-float-left.wd-lg-1, .position-float-right.wd-lg-1 { width:8.1vw; }
.position-float-left.wd-lg-2, .position-float-right.wd-lg-2 { width:16.4vw; }
.position-float-left.wd-lg-3, .position-float-right.wd-lg-3 { width:24.7vw; }
.position-float-left.wd-lg-4, .position-float-right.wd-lg-4 { width:33.1vw; }
.position-float-left.wd-lg-5, .position-float-right.wd-lg-5 { width:41.4vw; }
.position-float-left.wd-lg-6, .position-float-right.wd-lg-6 { width:48.7vw; }
.position-float-left.wd-lg-7, .position-float-right.wd-lg-7 { width:58.1vw; }
.position-float-left.wd-lg-8, .position-float-right.wd-lg-8 { width:66.4vw; }
.position-float-left.wd-lg-9, .position-float-right.wd-lg-9 { width:74.7vw; }

.position-float-left.wd-lg-10, .position-float-left.wd-lg-11, .position-float-left.wd-lg-12 {  float:none; margin-left:0; margin-right:auto; } 
.position-float-right.wd-lg-10, .position-float-right.wd-lg-11, .position-float-right.wd-lg-12 {  float:none; margin-left:auto; margin-right:0; } 
}

@media (min-width:[$media-xl-min]) {
.wd-xl-1 { width:8.1%; }
.wd-xl-2 { width:16.4%; }
.wd-xl-3 { width:24.7%; }
.wd-xl-4 { width:33.1%; }
.wd-xl-5 { width:41.4%; }
.wd-xl-6 { width:48.7%; }
.wd-xl-7 { width:58.1%; }
.wd-xl-8 { width:66.4%; }
.wd-xl-9 { width:74.7%; }
.wd-xl-10 { width:83.1%; }
.wd-xl-11 { width:91.4%; }
.wd-xl-12 { width:100%; }

.position-float-left.wd-xl-1, .position-float-right.wd-xl-1 { width:8.1vw; }
.position-float-left.wd-xl-2, .position-float-right.wd-xl-2 { width:16.4vw; }
.position-float-left.wd-xl-3, .position-float-right.wd-xl-3 { width:24.7vw; }
.position-float-left.wd-xl-4, .position-float-right.wd-xl-4 { width:33.1vw; }
.position-float-left.wd-xl-5, .position-float-right.wd-xl-5 { width:41.4vw; }
.position-float-left.wd-xl-6, .position-float-right.wd-xl-6 { width:48.7vw; }
.position-float-left.wd-xl-7, .position-float-right.wd-xl-7 { width:58.1vw; }
.position-float-left.wd-xl-8, .position-float-right.wd-xl-8 { width:66.4vw; }
.position-float-left.wd-xl-9, .position-float-right.wd-xl-9 { width:74.7vw; }

.position-float-left.wd-xl-10, .position-float-left.wd-xl-11, .position-float-left.wd-xl-12 {  float:none; margin-left:0; margin-right:auto; } 
.position-float-right.wd-xl-10, .position-float-right.wd-xl-11, .position-float-right.wd-xl-12 {  float:none; margin-left:auto; margin-right:0; } 

.wd-xl-hidden { display:none; }
}
@media (max-width:[$media-sm-max]) {
.wd-sm-hidden { display:none; }
}
@media (min-width:[$media-md-min]) and (max-width:[$media-md-max]) {
.wd-md-hidden { display:none; }
}
@media (min-width:[$media-lg-min]) and (max-width:[$media-lg-max]) {
.wd-lg-hidden { display:none; }
}


/* Box */
.box-display-inline { display:inline-block; vertical-align:top; }
.box-display-inline > div { display:inline-block; }
.caption { display:inline-block; vertical-align:top; }
.box-shadow-off { box-shadow:none; }
.box-shadow-on { box-shadow:[$box-shadow]; }
.box-shadow-create { border:1px solid #000; background-color:#fff; color:#000; }
/* Inline list */
.list-layout-inline { display:inline-block; }
.list-layout-inline > .details { display:inline-block; vertical-align:top; }
.list-layout-inline.list-align-center { text-align:center; }
.list-layout-inline.list-align-right { text-align:right; }

.list-layout-inline.col-sm-auto > .details, .list-layout-inline.col-sm-auto > .details > .alignment { width:auto; }

.list-layout-inline.col-sm-1 > .details { width:100%; }
.list-layout-inline.col-sm-2 > .details { width:49.8%; }
.list-layout-inline.col-sm-3 > .details { width:33.1%; }
.list-layout-inline.col-sm-4 > .details { width:24.7%; }
.list-layout-inline.col-sm-5 > .details { width:19.7%; }
.list-layout-inline.col-sm-6 > .details { width:16.4%; }

@media (min-width:[$media-md-min]) {
.list-layout-inline.col-md-1 > .details { width:100%; }
.list-layout-inline.col-md-2 > .details { width:49.8%; }
.list-layout-inline.col-md-3 > .details { width:33.1%; }
.list-layout-inline.col-md-4 > .details { width:24.7%; }
.list-layout-inline.col-md-5 > .details { width:19.7%; }
.list-layout-inline.col-md-6 > .details { width:16.4%; }
}

@media (min-width:[$media-lg-min]) {
.list-layout-inline.col-lg-1 > .details { width:100%; }
.list-layout-inline.col-lg-2 > .details { width:49.8%; }
.list-layout-inline.col-lg-3 > .details { width:33.1%; }
.list-layout-inline.col-lg-4 > .details { width:24.7%; }
.list-layout-inline.col-lg-5 > .details { width:19.7%; }
.list-layout-inline.col-lg-6 > .details { width:16.4%; }
}

@media (min-width:[$media-xl-min]) {
.list-layout-inline.col-xl-1 > .details { width:100%; }
.list-layout-inline.col-xl-2 > .details { width:49.8%; }
.list-layout-inline.col-xl-3 > .details { width:33.1%; }
.list-layout-inline.col-xl-4 > .details { width:24.7%; }
.list-layout-inline.col-xl-5 > .details { width:19.7%; }
.list-layout-inline.col-xl-6 > .details { width:16.4%; }
}

.list-layout-inline > .details { padding-top:calc([$list-row-gap] / 2); padding-right:calc([$list-column-gap] / 2); padding-bottom:calc([$list-row-gap] / 2); padding-left:calc([$list-column-gap] / 2); }

.list-layout-inline.list-column-gap-000 > .details { padding-left:0; padding-right:0; }
.list-layout-inline.list-column-gap-012 > .details { padding-left:0.0625rem; padding-right:0.0625rem; }
.list-layout-inline.list-column-gap-025 > .details { padding-left:.125rem; padding-right:.125rem; }
.list-layout-inline.list-column-gap-037 > .details { padding-left:.1875rem; padding-right:.1875rem; }
.list-layout-inline.list-column-gap-050 > .details { padding-left:.25rem; padding-right:.25rem; }
.list-column-gap-075 > .details { padding-left:.375rem; padding-right:.375rem; }
.list-layout-inline.list-column-gap-100 > .details { padding-left:.5rem; padding-right:.5rem; }
.list-layout-inline.list-column-gap-125 > .details { padding-left:.625rem; padding-right:.625rem; }
.list-layout-inline.list-column-gap-150 > .details { padding-left:.75rem; padding-right:.75rem; }
.list-layout-inline.list-column-gap-200 > .details { padding-left:1rem; padding-right:1rem; }

.list-layout-inline.list-row-gap-000 > .details { padding-top:0; padding-bottom:0; }
.list-layout-inline.list-row-gap-012 > .details { padding-top:.0625rem; padding-bottom:0.0625rem; }
.list-layout-inline.list-row-gap-025 > .details { padding-top:.125rem; padding-bottom:0.125rem; }
.list-layout-inline.list-row-gap-037 > .details { padding-top:.1875rem; padding-bottom:0.1875rem; }
.list-layout-inline.list-row-gap-050 > .details { padding-top:.25rem; padding-bottom:0.25rem; }
.list-layout-inline.list-row-gap-075 > .details { padding-top:0.375rem; padding-bottom:.375rem; }
.list-layout-inline.list-row-gap-100 > .details { padding-top:.5rem; padding-bottom:0.5rem; }
.list-layout-inline.list-row-gap-125 > .details { padding-top:.625rem; padding-bottom:0.625rem; }
.list-layout-inline.list-row-gap-150 > .details { padding-top:.75rem; padding-bottom:0.75rem; }
.list-layout-inline.list-row-gap-200 > .details { padding-top:1rem; padding-bottom:1rem; }


/* Grid list */
.list-layout-grid { display:grid; justify-content:start; }
.list-layout-grid.list-align-center { justify-content:center; }
.list-layout-grid.list-align-right { justify-content:end; }

.list-layout-grid > .details { width:auto; }
.list-layout-grid { grid-template-columns:1fr; } 
.list-layout-grid.col-sm-2 { grid-template-columns:1fr 1fr; } 
.list-layout-grid.col-sm-3 { grid-template-columns:1fr 1fr 1fr; } 
.list-layout-grid.col-sm-4 { grid-template-columns:1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-sm-5 { grid-template-columns:1fr 1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-sm-6 { grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr; } 

@media (min-width:[$media-md-min]) {
.list-layout-grid.col-md-1 { grid-template-columns:1fr; } 
.list-layout-grid.col-md-2 { grid-template-columns:1fr 1fr; } 
.list-layout-grid.col-md-3 { grid-template-columns:1fr 1fr 1fr; } 
.list-layout-grid.col-md-4 { grid-template-columns:1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-md-5 { grid-template-columns:1fr 1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-md-6 { grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr; } 
}
@media (min-width:[$media-md-min]) {
.list-layout-grid.col-lg-1 { grid-template-columns:1fr; } 
.list-layout-grid.col-lg-2 { grid-template-columns:1fr 1fr; } 
.list-layout-grid.col-lg-3 { grid-template-columns:1fr 1fr 1fr; } 
.list-layout-grid.col-lg-4 { grid-template-columns:1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-lg-5 { grid-template-columns:1fr 1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-lg-6 { grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr; } 
}
@media (min-width:[$media-xl-min]) {
.list-layout-grid.col-xl-1 { grid-template-columns:1fr; } 
.list-layout-grid.col-xl-2 { grid-template-columns:1fr 1fr; } 
.list-layout-grid.col-xl-3 { grid-template-columns:1fr 1fr 1fr; } 
.list-layout-grid.col-xl-4 { grid-template-columns:1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-xl-5 { grid-template-columns:1fr 1fr 1fr 1fr 1fr; } 
.list-layout-grid.col-xl-6 { grid-template-columns:1fr 1fr 1fr 1fr 1fr 1fr; } 
}

.list-layout-grid { grid-row-gap:[$list-row-gap]; grid-column-gap:[$list-column-gap]; }

.list-layout-grid.list-column-gap-000 { grid-column-gap:0; }
.list-layout-grid.list-column-gap-012 { grid-column-gap:0.125rem; }
.list-layout-grid.list-column-gap-025 { grid-column-gap:0.25rem; }
.list-layout-grid.list-column-gap-037 { grid-column-gap:0.375rem; }
.list-layout-grid.list-column-gap-050 { grid-column-gap:0.5rem;  }
.list-layout-grid.list-column-gap-075 { grid-column-gap:0.75rem;  }
.list-layout-grid.list-column-gap-100 { grid-column-gap:1rem; }
.list-layout-grid.list-column-gap-125 { grid-column-gap:1.25rem; }
.list-layout-grid.list-column-gap-150 { grid-column-gap:1.5rem; }
.list-layout-grid.list-column-gap-200 { grid-column-gap:2rem; }

.list-layout-grid.list-row-gap-000 { grid-row-gap:0; }
.list-layout-grid.list-row-gap-012 { grid-row-gap:0.125rem; }
.list-layout-grid.list-row-gap-025 { grid-row-gap:0.25rem; }
.list-layout-grid.list-row-gap-037 { grid-row-gap:0.375rem; }
.list-layout-grid.list-row-gap-050 { grid-row-gap:0.5rem;  }
.list-layout-grid.list-row-gap-075 { grid-row-gap:0.75rem;  }
.list-layout-grid.list-row-gap-100 { grid-row-gap:1rem; }
.list-layout-grid.list-row-gap-125 { grid-row-gap:1.25rem; }
.list-layout-grid.list-row-gap-150 { grid-row-gap:1.5rem; }
.list-layout-grid.list-row-gap-200 { grid-row-gap:2rem; }

/* Columns list */
.list-layout-columns { display:columns; }
.columns { display:columns; position:relative; width:100%; padding:0; }

.columns, .list-layout-columns { column-count:1; }
.columns-sm-2, .list-layout-columns.col-sm-2 { column-count:2; }
.columns-sm-3, .list-layout-columns.col-sm-3 { column-count:3; }
.columns-sm-4, .list-layout-columns.col-sm-4 { column-count:4; }
.columns-sm-5, .list-layout-columns.col-sm-5 { column-count:5; }
.columns-sm-6, .list-layout-columns.col-sm-6 { column-count:6; }
@media (min-width:[$media-md-min]) {
.columns-md-1, .list-layout-columns.col-md-1 { column-count:1; }
.columns-md-2, .list-layout-columns.col-md-2 { column-count:2; }
.columns-md-3, .list-layout-columns.col-md-3 { column-count:3; }
.columns-md-4, .list-layout-columns.col-md-4 { column-count:4; }
.columns-md-5, .list-layout-columns.col-md-5 { column-count:5; }
.columns-md-6, .list-layout-columns.col-md-6 { column-count:6; }
}
@media (min-width:[$media-lg-min]) {
.columns-lg-1, .list-layout-columns.col-lg-1 { column-count:1; }
.columns-lg-2, .list-layout-columns.col-lg-2 { column-count:2; }
.columns-lg-3, .list-layout-columns.col-lg-3 { column-count:3; }
.columns-lg-4, .list-layout-columns.col-lg-4 { column-count:4; }
.columns-lg-5, .list-layout-columns.col-lg-5 { column-count:5; }
.columns-lg-6, .list-layout-columns.col-lg-6 { column-count:6; }
}
@media (min-width:[$media-xl-min]) {
.columns-xl-1, .list-layout-columns.col-xl-1 { column-count:1; }
.columns-xl-2, .list-layout-columns.col-xl-2 { column-count:2; }
.columns-xl-3, .list-layout-columns.col-xl-3 { column-count:3; }
.columns-xl-4, .list-layout-columns.col-xl-4 { column-count:4; }
.columns-xl-5, .list-layout-columns.col-xl-5 { column-count:5; }
.columns-xl-6, .list-layout-columns.col-xl-6 { column-count:6; }
}

.columns, .list-layout-columns { column-gap:[$list-column-gap]; }
.list-layout-columns > .details { padding-top:calc([$list-row-gap] / 2); padding-bottom:calc([$list-row-gap] / 2); }

.list-layout-columns.list-column-gap-000 { column-gap:0; }
.list-layout-columns.list-column-gap-012 { column-gap:0.125rem; }
.list-layout-columns.list-column-gap-025 { column-gap:0.25rem; }
.list-layout-columns.list-column-gap-037 { column-gap:0.375rem; }
.list-layout-columns.list-column-gap-050 { column-gap:0.5rem;  }
.list-layout-columns.list-column-gap-075 { column-gap:0.75rem;  }
.list-layout-columns.list-column-gap-100 { column-gap:1rem; }
.list-layout-columns.list-column-gap-125 { column-gap:1.25rem; }
.list-layout-columns.list-column-gap-150 { column-gap:1.5rem; }
.list-layout-columns.list-column-gap-200 { column-gap:2rem; }

.list-layout-columns.list-row-gap-000 > .details { padding-top:0; padding-bottom:0; }
.list-layout-columns.list-row-gap-012 > .details { padding-top:.0625rem; padding-bottom:0.0625rem; }
.list-layout-columns.list-row-gap-025 > .details { padding-top:.125rem; padding-bottom:0.125rem; }
.list-layout-columns.list-row-gap-037 > .details { padding-top:.1875rem; padding-bottom:0.1875rem; }
.list-layout-columns.list-row-gap-050 > .details { padding-top:.25rem; padding-bottom:0.25rem; }
.list-layout-columns.list-row-gap-075 > .details { padding-top:0.375rem; padding-bottom:.375rem; }
.list-layout-columns.list-row-gap-100 > .details { padding-top:.5rem; padding-bottom:0.5rem; }
.list-layout-columns.list-row-gap-125 > .details { padding-top:.625rem; padding-bottom:0.625rem; }
.list-layout-columns.list-row-gap-150 > .details { padding-top:.75rem; padding-bottom:0.75rem; }
.list-layout-columns.list-row-gap-200 > .details { padding-top:1rem; padding-bottom:1rem; }

/* flexible list */
.list-layout-flex { display:flex; flex-wrap:wrap; justify-content:flex-start; }
.list-layout-flex.list-align-center { justify-content:center; }
 .list-layout-flex.list-align-right { justify-content:flex-end; }

.list-layout-flex.col-sm-auto > .details, .list-layout-flex.col-sm-auto > .details > .alignment { width:auto; }

.list-layout-flex.col-sm-1 > .details { flex-basis:100%; }
.list-layout-flex.col-sm-2 > .details { flex-basis:49.8%; }
.list-layout-flex.col-sm-3 > .details { flex-basis:33.1%; }
.list-layout-flex.col-sm-4 > .details { flex-basis:24.7%; }
.list-layout-flex.col-sm-5 > .details { flex-basis:19.7%; }
.list-layout-flex.col-sm-6 > .details { flex-basis:16.4%; }

@media (min-width:[$media-md-min]) {
.list-layout-flex.col-md-1 > .details { flex-basis:99.8%; }
.list-layout-flex.col-md-2 > .details { flex-basis:49.8%; }
.list-layout-flex.col-md-3 > .details { flex-basis:33.1%; }
.list-layout-flex.col-md-4 > .details { flex-basis:24.7%; }
.list-layout-flex.col-md-5 > .details { flex-basis:19.7%; }
.list-layout-flex.col-md-6 > .details { flex-basis:16.4%; }
}

@media (min-width:[$media-lg-min]) {
.list-layout-flex.col-lg-1 > .details { flex-basis:99.8%; }
.list-layout-flex.col-lg-2 > .details { flex-basis:49.8%; }
.list-layout-flex.col-lg-3 > .details { flex-basis:33.1%; }
.list-layout-flex.col-lg-4 > .details { flex-basis:24.7%; }
.list-layout-flex.col-lg-5 > .details { flex-basis:19.7%; }
.list-layout-flex.col-lg-6 > .details { flex-basis:16.4%; }
}

@media (min-width:[$media-xl-min]) {
.list-layout-flex.col-xl-1 > .details { flex-basis:99.8%; }
.list-layout-flex.col-xl-2 > .details { flex-basis:49.8%; }
.list-layout-flex.col-xl-3 > .details { flex-basis:33.1%; }
.list-layout-flex.col-xl-4 > .details { flex-basis:24.7%; }
.list-layout-flex.col-xl-5 > .details { flex-basis:19.7%; }
.list-layout-flex.col-xl-6 > .details { flex-basis:16.4%; }
}

.list-layout-flex > .details { padding-top:calc([$list-row-gap] / 2); padding-right:calc([$list-column-gap] / 2); padding-bottom:calc([$list-row-gap] / 2); padding-left:calc([$list-column-gap] / 2); }

.list-layout-flex.list-column-gap-000 > .details { padding-left:0; padding-right:0; }
.list-layout-flex.list-column-gap-012 > .details { padding-left:0.0625rem; padding-right:0.0625rem; }
.list-layout-flex.list-column-gap-025 > .details { padding-left:.125rem; padding-right:.125rem; }
.list-layout-flex.list-column-gap-037 > .details { padding-left:.1875rem; padding-right:.1875rem; }
.list-layout-flex.list-column-gap-050 > .details { padding-left:.25rem; padding-right:.25rem; }
.list-column-gap-075 > .details { padding-left:.375rem; padding-right:.375rem; }
.list-layout-flex.list-column-gap-100 > .details { padding-left:.5rem; padding-right:.5rem; }
.list-layout-flex.list-column-gap-125 > .details { padding-left:.625rem; padding-right:.625rem; }
.list-layout-flex.list-column-gap-150 > .details { padding-left:.75rem; padding-right:.75rem; }
.list-layout-flex.list-column-gap-200 > .details { padding-left:1rem; padding-right:1rem; }

.list-layout-flex.list-row-gap-000 > .details { padding-top:0; padding-bottom:0; }
.list-layout-flex.list-row-gap-012 > .details { padding-top:.0625rem; padding-bottom:0.0625rem; }
.list-layout-flex.list-row-gap-025 > .details { padding-top:.125rem; padding-bottom:0.125rem; }
.list-layout-flex.list-row-gap-037 > .details { padding-top:.1875rem; padding-bottom:0.1875rem; }
.list-layout-flex.list-row-gap-050 > .details { padding-top:.25rem; padding-bottom:0.25rem; }
.list-layout-flex.list-row-gap-075 > .details { padding-top:0.375rem; padding-bottom:.375rem; }
.list-layout-flex.list-row-gap-100 > .details { padding-top:.5rem; padding-bottom:0.5rem; }
.list-layout-flex.list-row-gap-125 > .details { padding-top:.625rem; padding-bottom:0.625rem; }
.list-layout-flex.list-row-gap-150 > .details { padding-top:.75rem; padding-bottom:0.75rem; }
.list-layout-flex.list-row-gap-200 > .details { padding-top:1rem; padding-bottom:1rem; }


/* Details */
.details { position:relative; }
.details > .alignment > * { text-align:inherit; }
.details > .alignment > .button { width:100%; text-align:inherit; }

.details-horizontal-align-center .details { text-align:center; }
.details-horizontal-align-right .details { text-align:right; }
.details.vertical-align-center > .details > .alignment { top:50%;  transform:translateY(-50%); }
.details.vertical-align-bottom > .details > .alignment { top:100%;  transform:translateY(-100%); }


.img-shape-auto, .img-shape-elipse, .img-shape-thumbnail, .img-shape-square, .img-shape-circle, .img-shape-video { display:block; position:relative; width:100%; z-index:1; }
.img-shape-thumbnail:after, .img-shape-square:after, .img-shape-circle:after { content:""; display:block; padding-bottom:100%; }
.img-shape-video { content:""; display:block; padding-bottom:57.5%; }
.img-shape-auto > div, .img-shape-elipse > div { position:relative; width:100%; }
.img-shape-thumbnail > div, .img-shape-square > div, .img-shape-circle > div, .img-shape-video > :first-child { position:absolute; width:100%; height:100%; overflow:hidden; }

.img-shape-auto img, .img-shape-elipse img { width:100%; height:auto; }
.img-shape-thumbnail img.img-orientation-portrait { width:auto; height:100%; position:relative; left:50%; transform:translateX(-50%); }
.img-shape-thumbnail img.img-orientation-landscape { width:100%; height:auto; position:relative; top:50%; transform:translateY(-50%); }
.img-shape-square img.img-orientation-portrait, .img-shape-circle img.img-orientation-portrait { width:100%; height:auto; position:relative; top:50%; transform:translateY(-50%); }
.img-shape-square img.img-orientation-landscape, .img-shape-circle img.img-orientation-landscape { width:auto; height:100%; position:relative; left:50%; transform:translateX(-50%); }
.img-shape-elipse > div, .img-shape-circle > div { border-radius:50%; overflow:hidden;  }

.img-smoke:before { position:absolute; width:100%; height:100%; content:""; z-index:2; border-radius:inherit; }

hr.layer-separator { margin:0; width:100%; }
.info { display:inline-block; }
.info + .info { padding-left:.3rem; }

/* format*/
.format { position:relative; display:block; margin-bottom:[$paragraph-margin-botton]; width:100%; }
.format > p { line-height:inherit; margin-bottom:inherit; text-indent:inherit; text-align:inherit; }
.format > :last-child { margin-bottom:0; }
p > .format { display:inline; }
.format-font-default { font-family:[font_stack$paragraph-font-name]; }
.format-font-label { font-family:[font_stack$label-font-name]; }
.format-font-blockquote { font-family:[font_stack$blockquote-font-name]; }
.format-font-legend { font-family:[font_stack$legend-font-name]; }
.format-font-link { font-family:[font_stack$link-font-name]; }
.format-font-header { font-family:[font_stack$header-font-name]; }
.format-font-caption { font-family:[font_stack$caption-font-name]; }
.format-font-bar { font-family:[font_stack$bar-font-name]; }
.format-font-monospace { font-family:[font_stack$pre-font-name]; }



/* Layout */
#layout_system_bar { position:absolute; top:0; right:0; bottom:auto; left:0; height:[$bar-height]; line-height:[$bar-height]; z-index:2; padding-left:[$mod-padding-left]; }
#layout_system_bar > * { display:inline-block; position:relative; line-height:1em; vertical-align:middle; margin-right:0.5rem; }
#layout_system_bar img { display:inline-block; height:2rem; width:auto; }
#layout_document { position:absolute; top:[$bar-height]; right:0; bottom:0; left:0; z-index:1; }
#layout_system_icons { position:absolute; top:0; right:0; bottom:auto; left:auto; height:[$bar-height]; line-height:[$bar-height]; padding-right:[$mod-padding-right]; z-index:3;  }
#layout_system_icons > * { display:inline-block; position:relative; line-height:2em; margin-left:0.5rem; vertical-align:middle; }
#layout_system_icons img { display:inline-block; height:2em; width:auto; }
#layout_baloom { position:absolute; top:5rem; right:1rem; bottom:auto; left:1rem; text-align:right; z-index:4; }
.baloom { display:inline-block; }
.personalite { position:absolute; top:.3rem; right:.3rem; z-index:1001; width:1rem; height:1rem; color:#fff; background-color:rgba(0, 0, 0, 0.3); border:0.2rem solid #fff; font-family:serif; font-weight:bold; line-height:1rem; text-align:center; vertical-align:middle; font-size:1.5rem; }
.personalite-A, .personalite-I, .personalite-O { background-color:#888800; }
.personalite-B, .personalite-C, .personalite-F, .personalite-G, .personalite-H { background-color:#008800; }
.personalite-D, .personalite-M { background-color:#880000; }

/* Formularies */
.form-layout-grid { position:relative; width:100%; }
.form-control { position:relative; display:block; width:100%; }

.form-control-help { padding-right:3em; }
.form-col-help { position:absolute; top:0.6rem; right:0; font-family:serif; font-weight:bold; }

.form-col-input { width:100%; }
.form-col-input-half { width:50%; }

.form-col-reverse-input { display:inline-block; width:10%; }
.form-col-reverse-label { display:inline-block; width:80%; }
.form-col-full-label { display:inline-block; width:90%; }
.form-col-full-input { display:inline-block; width:100%; }

@media (min-width:[$media-md-min]) {
.form-layout-grid .form-col-legend { display:inline-block; width:30%; }
.form-layout-grid .form-col-input { display:inline-block; width:60%; }
.form-layout-grid .form-col-input-half { display:inline-block; width:30%; }
}

.form-layout-box .form-col-legend { display:inline-block; width:40%; }
.form-layout-box .form-col-input { display:inline-block; width:60%; }
.form-layout-box .form-col-input-half { display:inline-block; width:60%; }

.form-layout-inline { width:auto; }
.form-layout-inline * { display:inline-block; width:auto; }

.input { min-height:2.4375rem; margin:0 0 1rem; appearance:none;  }
.input:focus { outline:none; }
textarea { max-width:100%;  }
textarea[]rows] { height:auto;  }
input:disabled, input[]readonly], textarea:disabled, textarea[]readonly] { background-color:#e6e6e6; cursor:not-allowed;  }
button, .button { appearance:none; }
[]type="file"], []type="checkbox"], []type="radio"] { margin:0 0 1rem;  }
[]type="checkbox"] + label, []type="radio"] + label { display:inline-block; vertical-align:baseline; margin-left:0.5rem; margin-right:1rem; margin-bottom:0;  }
[]type="checkbox"] + label[]for], []type="radio"] + label[]for] { cursor:pointer;  }
label > []type="checkbox"], label > []type="radio"] { margin-right:0.5rem;  }
[]type="file"] { width:100%;  }
label { display:block; }
label.middle { margin:0 0 1rem; padding:0.5625rem 0;  }
fieldset { margin:0; padding:0; border:0;  }
legend { max-width:100%; margin-bottom:0.5rem;  }
.fieldset { margin:1.125rem 0; padding:1.25rem; border:1px solid #cacaca;  }
.fieldset legend { margin:0; margin-left:-0.1875rem; padding:0 0.1875rem;  }
select { height:2.4375rem; margin:0 0 1rem; padding:0.5rem; -webkit-appearance:none; -moz-appearance:none; appearance:none; background-image:url("data:image/svg+xml; utf8,<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="32" height="24" viewBox="0 0 32 24"><polygon points="0,0 32,0 16,24" style="fill:rgb%28138, 138, 138%29"></polygon></svg>"); background-origin:content-box; background-position:right -1rem center; background-repeat:no-repeat; background-size:9px 6px; padding-right:1.5rem; }
select::-ms-expand { display:none;  }
select[]multiple] { height:auto; background-image:none;  }

/* Ending definitions */
[]hidden] { display:none !important; }
form > label { display:none; }
'