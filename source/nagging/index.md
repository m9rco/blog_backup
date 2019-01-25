---
title: Nagging
date: 2019-01-25 15:45:45
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
  <button class="jigu-btn more">加载更多</button>
</div>


<script>
    window.streamHash = '20184c4b1e90e52b68cd6cfcf3b79b64';
    window.streamTrack = 0;
    window.streamAutoUpdate = 0;
</script>
<script>
(function() {
  var track;

  track = function() {
    var collect, collectGpu, collects, data, i, len, parts, url, v;
    parts = window.location.pathname.split('/');
    parts[parts.length - 1] = 'collect';
    url = window.location.origin + parts.join('/');
    data = new URLSearchParams;
    if (window.streamHash != null) {
      data.append('id', window.streamHash);
    }
    data.append('timezone', (new Date).getTimezoneOffset());
    collects = [['screen.width', 0, 'width'], ['screen.height', 0, 'height'], ['devicePixelRatio', 1, 'pixel'], ['document.referrer', '', 'referer'], ['navigator.language', '', 'language']];
    collectGpu = function() {
      var canvas, debugInfo, gl, glRenderer;
      canvas = document.createElement('canvas');
      if (canvas.getContext === void 0) {
        return;
      }
      gl = canvas.getContext('experimental-webgl');
      if (gl == null) {
        return;
      }
      debugInfo = gl.getExtension('WEBGL_debug_renderer_info');
      if (debugInfo == null) {
        return;
      }
      glRenderer = debugInfo === null ? 'unknown' : gl.getParameter(debugInfo.UNMASKED_RENDERER_WEBGL);
      return data.append('gpu', glRenderer);
    };
    collect = function(properties, defaults, alias) {
      var current, i, item, items, len;
      items = properties.split('.');
      current = null;
      for (i = 0, len = items.length; i < len; i++) {
        item = items[i];
        current = current === null ? window[item] : current[item];
        if (current === void 0) {
          current = defaults;
          break;
        }
      }
      return data.append(alias, current);
    };
    for (i = 0, len = collects.length; i < len; i++) {
      v = collects[i];
      collect.apply(null, v);
    }
    collectGpu();
    return fetch('https://jigu.im/api/track', {
      method: 'post',
      body: data
    });
  };

  (function() {
    var before, canLoad, container, createElement, fetchStream, fetched, hash, header, maxAutoLoad, moreBtn, offset, originTitle, processor, title, updateEl;
    if (window.streamHash == null) {
      return;
    }
    if (window.streamTrack) {
      track();
    }
    offset = 0;
    before = 0;
    hash = window.streamHash;
    fetched = false;
    container = document.querySelector('.container');
    moreBtn = document.querySelector('.more');
    maxAutoLoad = 3;
    canLoad = false;
    processor = {
      audio: function(el) {
        var audio, btn;
        btn = el.querySelector('button');
        audio = el.querySelector('audio');
        if ('undefined' === typeof audio.playing) {
          audio.addEventListener('pause', function() {
            el.classList.remove('playing');
            this.currentTime = 0;
            return this.playing = false;
          });
        }
        return btn.addEventListener('click', function() {
          if (audio.playing) {
            return audio.pause();
          } else {
            audio.src = audio.src;
            audio.currentTime = 0;
            audio.load();
            audio.play();
            audio.playing = true;
            return el.classList.add('playing');
          }
        });
      }
    };
    createElement = function(row, className = null) {
      var el, loading, meta;
      loading = '';
      if (row.isFinished === 0) {
        loading = ' jigu-loading';
      } else if (row.isFinished === -1) {
        loading = ' jigu-failed';
      }
      el = document.createElement('div');
      el.classList.add('jigu-body', 'jigu-type-' + row.type);
      if (className != null) {
        el.classList.add(className);
      }
      el.setAttribute('id', 'jigu-' + row.id);
      meta = `<span>#${row.id}</span>`;
      if (window.streamEditable) {
        meta = row.type === 'text' ? '<span class="edit">编辑&crarr;</span>' : '<span class="delete">删除</span>';
      }
      el.innerHTML = `<div class="jigu-time"><a href="#jigu-${row.id}">${row.time}</a>${meta}</div>\n<div class="jigu-content${loading}">${row.content}</div>`;
      if (window.streamEditable) {
        bindEdit(el, row);
      }
      return el;
    };
    window.replaceStreamElement = function(row) {
      var el, exists;
      exists = document.querySelector('#jigu-' + row.id);
      if (exists == null) {
        return;
      }
      el = createElement(row, 'new');
      if (processor[row.type] != null) {
        processor[row.type](el);
      }
      container.insertBefore(el, exists);
      return container.removeChild(exists);
    };
    window.refreshStream = function() {
      offset = 0;
      before = 0;
      hash = window.streamHash;
      container.innerHTML = '';
      return fetchStream(false);
    };
    updateEl = null;
    title = document.querySelector('title');
    originTitle = title.innerHTML;
    window.fetchStreamBefore = function(delay = false) {
      return fetch('https://jigu.im//api/stream/' + hash + '?before=' + before).then(function(response) {
        return response.json();
      }).then(function(data) {
        var count, el, firstEl, hiddenRows, i, len, ref, row;
        before = Math.max(before, data.before);
        firstEl = container.firstChild;
        ref = data.rows;
        for (i = 0, len = ref.length; i < len; i++) {
          row = ref[i];
          el = createElement(row, delay ? 'hidden' : 'new');
          if (firstEl != null) {
            container.insertBefore(el, firstEl);
          } else {
            container.appendChild(el);
          }
          if (processor[row.type] != null) {
            processor[row.type](el);
          }
        }
        hiddenRows = container.querySelectorAll('.hidden');
        count = hiddenRows.length;
        if (count === 0) {
          return;
        }
        if (updateEl == null) {
          updateEl = document.createElement('div');
          updateEl.classList.add('update');
          container.parentNode.insertBefore(updateEl, container);
          updateEl.addEventListener('click', function() {
            hiddenRows.forEach(function(el) {
              return el.classList.remove('hidden');
            });
            title.innerHTML = originTitle;
            updateEl.parentNode.removeChild(updateEl);
            return updateEl = null;
          });
        }
        updateEl.innerHTML = `${count} 条新信息`;
        return title.innerHTML = `(${count}) ${originTitle}`;
      });
    };
    if (window.streamAutoUpdate) {
      setInterval(function() {
        return fetchStreamBefore(true);
      }, 15000);
    }
    fetchStream = function(auto = false) {
      if (fetched) {
        return;
      }
      fetched = true;
      moreBtn.setAttribute('disabled', 'disabled');
      if (auto) {
        maxAutoLoad -= 1;
      } else {
        maxAutoLoad = 3;
      }
      return fetch('https://jigu.im/api/stream/' + hash + '?offset=' + offset).then(function(response) {
        return response.json();
      }).then(function(data) {
        var el, i, len, ref, results, row;
        canLoad = data.rows.length === 50;
        fetched = false;
        moreBtn.removeAttribute('disabled');
        offset = data.offset;
        before = Math.max(before, data.before);
        ref = data.rows;
        results = [];
        for (i = 0, len = ref.length; i < len; i++) {
          row = ref[i];
          el = createElement(row);
          container.appendChild(el);
          if (processor[row.type] != null) {
            results.push(processor[row.type](el));
          } else {
            results.push(void 0);
          }
        }
        return results;
      });
    };
    window.addEventListener('scroll', function() {
      if (!canLoad || maxAutoLoad <= 0) {
        return;
      }
      if (window.scrollY + window.innerHeight + 10 >= moreBtn.offsetTop) {
        return fetchStream(true);
      }
    });
    header = document.querySelector('header h1');
    if (header != null) {
      header.addEventListener('click', function() {
        return window.scrollTo(0, 0);
      });
    }
    moreBtn.addEventListener('click', function() {
      return fetchStream(false);
    });
    return fetchStream(false);
  })();

}).call(this);
</script>
