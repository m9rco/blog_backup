layout: post
slug: "d"
title: 个人作品合集
noDate: "true"
---
<style type="text/css">
.index {
    width: 258px;
    margin: 0 auto;
    padding-top: 50px;
}

.index .summary {
    font-size: 14px;
    margin-top: 20px;
    line-height: 20px;
    color: #777;
}

.index img {
    width: 258px;
    height: 258px;
}

.summary h2 {
    font-size: 18px;
    padding: 0;
    margin: 0 0 20px 0;
    font-weight: normal;
    color: #222;
    animation: 1s linear 0s normal forwards fadeIn;
    line-height: 24px;
    height: 48px;
    overflow: hidden;
    text-align: center;
}

.summary h3:first-child {
    margin-bottom: 0;
}

.summary h3:nth-child(2) {
    margin-top: 0;
}

.summary h3 {
    font-size: 12px;
    text-align: center;
    color: #999;
    font-weight: normal;
    padding: 0;
    margin: 20px 0;
}

.summary h4 {
    text-align: center;
    font-size: 15px;
    font-weight: normal;
    padding: 0;
    margin: 20px 0 0 0;
}

.summary h4 a {
    color: #777;
}

.wrapper {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
}

.container {
    max-width: 740px;
    margin: 0 auto;
    min-height: 100%;
    padding: 61px 20px 10px 20px;
}

footer {
    position: fixed;
    width: 100%;
    text-align: center;
}

footer h1 {
    width: 100%;
    max-width: 740px;
    margin: 0 auto;
    font-size: 20px;
    line-height: 40px;
    font-weight: normal;
    background: #EEE;
    border-bottom: 1px solid #DDD;
    opacity: 0.95;
    text-transform: uppercase;
    cursor: pointer;
}

footer .code {
    max-width: 600px;
    text-align: left;
    width: 100%;
    font-size: 12px;
    resize: none;
    padding: 10px;
    border: 1px solid #ddd;
    border-top: none;
    display: none;
    font-family: "Source Code Pro",Consolas,Menlo,Monaco,"Courier New",monospace;
    background: #FFF;
    word-break: break-all;
}

footer .code label {
    padding: 0;
    margin: 0 0 5px 0;
    display: block;
}

footer .code label.before {
    margin-top: 10px;
}

footer .code input {
    font-family: "Source Code Pro",Consolas,Menlo,Monaco,"Courier New",monospace;
    width: 100%;
    border: 1px solid #333;
    border-bottom-color: #ddd;
    border-right-color: #ddd;
    background: #FFFFDD;
}

footer h1:hover + .code, footer .code:hover { display: inline-block }
.jigu-time{
    height: 10px;
}
.jigu-time a {
    font-size: 13px;
    color: #777;
    text-decoration: none;
    float: right;
}
.jigu-time a:hover { text-decoration: underline }
.jigu-time span {
    float: right;
    color: #CCC;
    font-size: 12px;
    display:none
}

.jigu-body:hover .jigu-time span { color: #777 ;display:none}

.jigu-content {
    padding: 10px;
    background: #9EDF60;
    border-radius: 5px;
    margin-top: 15px;
    display: inline-block;
}

.jigu-type-image .jigu-content, .jigu-type-audio .jigu-content { padding: 0; background: transparent }
.jigu-type-image .jigu-content.jigu-loading { padding: 10px; background: #9EDF60; color: #777; font-size: 12px }
.jigu-type-image .jigu-content img { border-radius: 5px; max-height: 300px; max-width: 300px; min-width: 50px; min-height: 50px; background: #EEE }
.jigu-type-audio audio { display: none }
.jigu-type-audio button { height: 41px; line-height: 41px; border: none; width: 70px; background: #9EDF60; border-radius: 5px; cursor: pointer }
.jigu-type-audio button:before {
    content: "\f027";
    line-height: 41px;
    color: #444;
    display: block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: 21px;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.jigu-type-audio.playing button:before {
    content: "\f028";
}

.jigu-body { margin-top: 20px; padding-bottom: 7px;border: 0;border-bottom: 1px solid rgba(102,128,153,0.1);}
.jigu-body:first-child { margin: 0 }
.jigu-page { margin-top: 20px; }
.jigu-page:first-child { margin: 0 }

.jigu-content:before { position:absolute; content: " "; left: 10px; top: -10px; border-top: 10px solid #FFF; border-right: 10px solid #9EDF60; display:none}
.jigu-content:after { position:absolute; content: " "; top: -10px; left: 20px; border-left: 10px solid #9EDF60; border-top: 10px solid #FFF;display:none}

.jigu-type-text .jig-content a { letter-spacing: 0; font-size: 14px; color: #555 }

.jigu-type-audio.playing .jigu-content button { background: #8ECC30 }
.jigu-type-audio.playing .jigu-content:before { border-right-color: #8ECC30 ;display:none}
.jigu-type-audio.playing .jigu-content:after { border-left-color: #8ECC30 ;display:none}

.jigu-btn { margin-top: 30px; border-radius: 3px; width: 100%; background: rgba(0,0,0,0); border: 1px solid #ccc;
    line-height: 30px; font-size: 16px; text-align: center; cursor: pointer; box-shadow: 0 0 1px #ddd; color: #777 }
.jigu-btn:hover { border-color: #999 }

.jigu-password {
    vertical-align: middle;
    text-align: center;
    padding: 100px 0;
}

.jigu-password input {
    width: 200px;
    border: 1px solid #333;
    border-bottom-color: #ddd;
    border-right-color: #ddd;
    height: 24px;
    line-height: 24px;
    padding: 0 5px;
}

.jigu-password button {
    margin-left: 10px;
    width: 80px;
    border: 1px solid #333;
    border-top-color: #ddd;
    border-left-color: #ddd;
    height: 24px;
    line-height: 24px;
    padding: 0 5px;
    background: #FFFFCC;
    cursor: pointer;
}

.jigu-password span {
    display: block;
    font-size: 13px;
    text-indent: -185px;
    margin-top: 5px;
}

.footer { text-align: center; font-size: 12px; line-height: 24px; margin-top: 10px; text-transform: uppercase; color: #BBB }
.footer a { color: #BBB; text-decoration: none }
</style>

<div class="container">
	<div id="jigu-box-20184c4b1e90e52b68cd6cfcf3b79b64"></div>
	<script async src="https://jigu.im/js/20184c4b1e90e52b68cd6cfcf3b79b64?size=20"></script>
</div>