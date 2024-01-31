# Capgemini_VimeoDisableControls module

Overwrite `videosrc.js` and change one line to append `controls=0` to the Vimeo URL when saved to the database.

Before:

```js
return "https://player.vimeo.com/video/" + vimeoRegExp.exec(value)[3] + "?title=0&byline=0&portrait=0" + (data.autoplay === "true" ? "&autoplay=1&autopause=0&muted=1" : "");
```

After:
```js
return "https://player.vimeo.com/video/" + vimeoRegExp.exec(value)[3] + "?controls=0&title=0&byline=0&portrait=0" + (data.autoplay === "true" ? "&autoplay=1&autopause=0&muted=1" : "");
```

