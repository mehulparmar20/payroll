function chartdata()
                            {

                                var Dial = function (container) {
                                    this.container = container;
                                    this.size = this.container.dataset.size;
                                    this.strokeWidth = this.size / 8;
                                    this.radius = (this.size / 2) - (this.strokeWidth / 2);
                                    this.value = this.container.dataset.value;
                                    this.direction = this.container.dataset.arrow;
                                    this.svg;
                                    this.defs;
                                    this.slice;
                                    this.overlay;
                                    this.text;
                                    this.arrow;
                                    this.create();
                                }

                                Dial.prototype.create = function () {
                                    this.createSvg();
                                    this.createDefs();
                                    this.createSlice();
                                    this.createOverlay();
                                    this.createText();
                                    this.createArrow();
                                    this.container.appendChild(this.svg);
                                };

                                Dial.prototype.createSvg = function () {
                                    var svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
                                    svg.setAttribute('width', this.size + 'px');
                                    svg.setAttribute('height', this.size + 'px');
                                    this.svg = svg;
                                };

                                Dial.prototype.createDefs = function () {
                                    var defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
                                    var linearGradient = document.createElementNS("http://www.w3.org/2000/svg", "linearGradient");
                                    linearGradient.setAttribute('id', 'gradient');
                                    var stop1 = document.createElementNS("http://www.w3.org/2000/svg", "stop");
                                    stop1.setAttribute('stop-color', '#6E4AE2');
                                    stop1.setAttribute('offset', '0%');
                                    linearGradient.appendChild(stop1);
                                    var stop2 = document.createElementNS("http://www.w3.org/2000/svg", "stop");
                                    stop2.setAttribute('stop-color', '#5c438c');
                                    stop2.setAttribute('offset', '100%');
                                    linearGradient.appendChild(stop2);
                                    var linearGradientBackground = document.createElementNS("http://www.w3.org/2000/svg", "linearGradient");
                                    linearGradientBackground.setAttribute('id', 'gradient-background');
                                    var stop1 = document.createElementNS("http://www.w3.org/2000/svg", "stop");
                                    stop1.setAttribute('stop-color', 'rgba(0, 0, 0, 0.2)');
                                    stop1.setAttribute('offset', '0%');
                                    linearGradientBackground.appendChild(stop1);
                                    var stop2 = document.createElementNS("http://www.w3.org/2000/svg", "stop");
                                    stop2.setAttribute('stop-color', 'rgba(0, 0, 0, 0.05)');
                                    stop2.setAttribute('offset', '100%');
                                    linearGradientBackground.appendChild(stop2);
                                    defs.appendChild(linearGradient);
                                    defs.appendChild(linearGradientBackground);
                                    this.svg.appendChild(defs);
                                    this.defs = defs;
                                };

                                Dial.prototype.createSlice = function () {
                                    var slice = document.createElementNS("http://www.w3.org/2000/svg", "path");
                                    slice.setAttribute('fill', 'none');
                                    slice.setAttribute('stroke', 'url(#gradient)');
                                    slice.setAttribute('stroke-width', this.strokeWidth);
                                    slice.setAttribute('transform', 'translate(' + this.strokeWidth / 2 + ',' + this.strokeWidth / 2 + ')');
                                    slice.setAttribute('class', 'animate-draw');
                                    this.svg.appendChild(slice);
                                    this.slice = slice;
                                };

                                Dial.prototype.createOverlay = function () {
                                    var r = this.size - (this.size / 2) - this.strokeWidth / 2;
                                    var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
                                    circle.setAttribute('cx', this.size / 2);
                                    circle.setAttribute('cy', this.size / 2);
                                    circle.setAttribute('r', r);
                                    circle.setAttribute('fill', 'url(#gradient-background)');
                                    this.svg.appendChild(circle);
                                    this.overlay = circle;
                                };

                                Dial.prototype.createText = function () {
                                    var fontSize = this.size / 3.5;
                                    var text = document.createElementNS("http://www.w3.org/2000/svg", "text");
                                    text.setAttribute('x', (this.size / 2) + fontSize / 7.5);
                                    text.setAttribute('y', (this.size / 2) + fontSize / 4);
                                    text.setAttribute('font-family', 'Century Gothic, Lato');
                                    text.setAttribute('font-size', fontSize);
                                    text.setAttribute('fill', '#5c438c');
                                    text.setAttribute('text-anchor', 'middle');
                                    var tspanSize = fontSize / 3;
                                    text.innerHTML = 0 + '<tspan font-size="' + tspanSize + '" dy="' + -tspanSize * 1.2 + '">min</tspan>';
                                    this.svg.appendChild(text);
                                    this.text = text;
                                };

                                Dial.prototype.createArrow = function () {
                                    var arrowSize = this.size / 10;
                                    var arrowYOffset, m;
                                    if (this.direction === 'up') {
                                        arrowYOffset = arrowSize / 2;
                                        m = -1;
                                    } else if (this.direction === 'down') {
                                        arrowYOffset = 0;
                                        m = 1;
                                    }
                                    var arrowPosX = ((this.size / 2) - arrowSize / 2);
                                    var arrowPosY = (this.size - this.size / 3) + arrowYOffset;
                                    var arrowDOffset = m * (arrowSize / 1.5);
                                    var arrow = document.createElementNS("http://www.w3.org/2000/svg", "path");
                                    arrow.setAttribute('d', 'M 0 0 ' + arrowSize + ' 0 ' + arrowSize / 2 + ' ' + arrowDOffset + ' 0 0 Z');
                                    arrow.setAttribute('fill', '#97F8F0');
                                    arrow.setAttribute('opacity', '0.6');
                                    arrow.setAttribute('transform', 'translate(' + arrowPosX + ',' + arrowPosY + ')');
                                    this.svg.appendChild(arrow);
                                    this.arrow = arrow;
                                };

                                Dial.prototype.animateStart = function () {
                                    var v = 0;
                                    var self = this;
                                    var intervalOne = setInterval(function () {
                                        var p = +(v / self.value).toFixed(2);
                                        var a = (p < 0.95) ? 2 - (2 * p) : 0.05;
                                        v += a;
                                        // Stop
                                        if (v >= +self.value) {
                                            v = self.value;
                                            clearInterval(intervalOne);
                                        }
                                        self.setValue(v);
                                    }, 10);
                                };

                                Dial.prototype.animateReset = function () {
                                    this.setValue(0);
                                };

                                Dial.prototype.polarToCartesian = function (centerX, centerY, radius, angleInDegrees) {
                                    var angleInRadians = (angleInDegrees - 90) * Math.PI / 180.0;
                                    return {
                                        x: centerX + (radius * Math.cos(angleInRadians)),
                                        y: centerY + (radius * Math.sin(angleInRadians))
                                    };
                                }

                                Dial.prototype.describeArc = function (x, y, radius, startAngle, endAngle) {
                                    var start = this.polarToCartesian(x, y, radius, endAngle);
                                    var end = this.polarToCartesian(x, y, radius, startAngle);
                                    var largeArcFlag = endAngle - startAngle <= 180 ? "0" : "1";
                                    var d = [
                                        "M", start.x, start.y,
                                        "A", radius, radius, 0, largeArcFlag, 0, end.x, end.y
                                    ].join(" ");
                                    return d;
                                }

                                Dial.prototype.setValue = function (value) {
                                    var c = (value / 100) * 360;
                                    if (c === 360)
                                        c = 359.99;
                                    var xy = this.size / 2 - this.strokeWidth / 2;
                                    var d = this.describeArc(xy, xy, xy, 180, 180 + c);
                                    this.slice.setAttribute('d', d);
                                    var tspanSize = (this.size / 3.5) / 3;
                                    this.text.innerHTML = Math.floor(value) + '<tspan font-size="' + tspanSize + '" dy="' + -tspanSize * 1.2 + '">min</tspan>';
                                };

                                var containers = document.getElementsByClassName("chart");
                                var dial = new Dial(containers[0]);
                                dial.animateStart();
                            }
                            // Attendance Chart
                            function attendance_chart()
                            {
                                !function($) {
                                    "use strict";

                                    var ChartJs = function() {};

                                    ChartJs.prototype.respChart = function(selector,type,data, options) {
                                        // get selector by context
                                        var ctx = selector.get(0).getContext("2d");
                                        // pointing parent container to make chart js inherit its width
                                        var container = $(selector).parent();

                                        // enable resizing matter
                                        $(window).resize( generateChart );

                                        // this function produce the responsive Chart JS
                                        function generateChart(){
                                            // make chart width fit with its container
                                            var ww = selector.attr('width', $(container).width() );
                                            switch(type){
                                                case 'Bar':
                                                    new Chart(ctx, {type: 'bar', data: data, options: options});
                                                    break;
                                            }
                                            // Initiate new chart or Redraw

                                        };
                                        // run function - render chart at first load
                                        generateChart();
                                    },
                                //init
                                ChartJs.prototype.init = function() {
                                    //barchart
                                    var barChart = {
                                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                                        datasets: [
                                            {
                                                label: "Attendance Analytics",
                                                backgroundColor: "rgba(2, 197, 141, 0.4)",
                                                borderColor: "#02c58d",
                                                borderWidth: 1,
                                                hoverBackgroundColor: "rgba(2, 197, 141, 0.5)",
                                                hoverBorderColor: "#02c58d",
                                                data: [55, 63, 83, 65, 76, 80, 50, 20, 50, 90, 75, 80]
                                            }
                                        ]
                                    };
                                    this.respChart($("#bar"),'Bar',barChart);
                                },
                                $.ChartJs = new ChartJs, $.ChartJs.Constructor = ChartJs

                            }(window.jQuery),

                            //initializing
                            function($) {
                                "use strict";
                                $.ChartJs.init()
                            }(window.jQuery);
    
                            }
                            
// Google Chart JAVASCRIPT

if (!window['googleLT_']) {
 window['googleLT_'] = (new Date()).getTime();
}
if (!window['google']) {
 window['google'] = {};
}
if (!window['google']['loader']) {
 window['google']['loader'] = {};
 google.loader.ServiceBase = 'https://www.google.com/uds';
 google.loader.GoogleApisBase = 'https://ajax.googleapis.com/ajax';
 google.loader.ApiKey = 'notsupplied';
 google.loader.KeyVerified = true;
 google.loader.LoadFailure = false;
 google.loader.Secure = true;
 google.loader.GoogleLocale = 'www.google.com';
 google.loader.ClientLocation = null;
 google.loader.AdditionalParams = '';
 (function() {
  var h = this || self,
   aa = /^[\w+/_-]+[=]{0,2}$/,
   l = null,
   m = function(a, b, c) {
    a = a.split(".");
    c = c || h;
    a[0] in c || "undefined" == typeof c.execScript || c.execScript("var " + a[0]);
    for (var d; a.length && (d = a.shift());) a.length || void 0 === b ? c = c[d] && c[d] !== Object.prototype[d] ? c[d] : c[d] = {} : c[d] = b
   },
   n = function(a, b, c) {
    a[b] = c
   };
  var p = function() {
   this.M = ""
  };
  p.prototype.toString = function() {
   return "SafeScript{" + this.M + "}"
  };
  p.prototype.c = function(a) {
   this.M = a;
   return this
  };
  (new p).c("");
  var q = /&/g,
   r = /</g,
   t = />/g,
   u = /"/g,
   v = /'/g,
   w = /\x00/g,
   ba = /[\x00&<>"']/;
  var x = function() {
   this.O = ""
  };
  x.prototype.toString = function() {
   return "SafeStyle{" + this.O + "}"
  };
  x.prototype.c = function(a) {
   this.O = a;
   return this
  };
  (new x).c("");
  var y = function() {
   this.N = ""
  };
  y.prototype.toString = function() {
   return "SafeStyleSheet{" + this.N + "}"
  };
  y.prototype.c = function(a) {
   this.N = a;
   return this
  };
  (new y).c("");
  var z = function() {
   this.L = ""
  };
  z.prototype.toString = function() {
   return "SafeHtml{" + this.L + "}"
  };
  z.prototype.c = function(a) {
   this.L = a;
   return this
  };
  (new z).c("<!DOCTYPE html>", 0);
  (new z).c("", 0);
  (new z).c("<br>", 0);
  var A = function(a, b) {
   b ? a = a.replace(q, "&amp;").replace(r, "&lt;").replace(t, "&gt;").replace(u, "&quot;").replace(v, "&#39;").replace(w, "&#0;") : ba.test(a) && (-1 != a.indexOf("&") && (a = a.replace(q, "&amp;")), -1 != a.indexOf("<") && (a = a.replace(r, "&lt;")), -1 != a.indexOf(">") && (a = a.replace(t, "&gt;")), -1 != a.indexOf('"') && (a = a.replace(u, "&quot;")), -1 != a.indexOf("'") && (a = a.replace(v, "&#39;")), -1 != a.indexOf("\x00") && (a = a.replace(w, "&#0;")));
   return a
  };

  function B(a) {
   return a in C ? C[a] : C[a] = -1 != navigator.userAgent.toLowerCase().indexOf(a)
  }
  var C = {};

  function E(a, b) {
   var c = function() {};
   c.prototype = b.prototype;
   a.ia = b.prototype;
   a.prototype = new c
  }

  function F(a, b, c) {
   var d = Array.prototype.slice.call(arguments, 2) || [];
   return function() {
    return a.apply(b, d.concat(Array.prototype.slice.call(arguments)))
   }
  }

  function G(a) {
   a = Error(a);
   a.toString = function() {
    return this.message
   };
   return a
  }

  function H(a, b) {
   a = a.split(/\./);
   for (var c = window, d = 0; d < a.length - 1; d++) c[a[d]] || (c[a[d]] = {}), c = c[a[d]];
   c[a[a.length - 1]] = b
  }

  function I(a, b, c) {
   a[b] = c
  }
  window.ca || (window.ca = {});
  m || (m = H);
  n || (n = I);
  google.loader.G = {};
  m("google.loader.callbacks", google.loader.G);
  var J = {},
   K = {};
  google.loader.eval = {};
  m("google.loader.eval", google.loader.eval);
  google.load = function(a, b, c) {
   function d(g) {
    var k = g.split(".");
    if (2 < k.length) throw G("Module: '" + g + "' not found!");
    "undefined" != typeof k[1] && (e = k[0], c.packages = c.packages || [], c.packages.push(k[1]))
   }
   var e = a;
   c = c || {};
   if (a instanceof Array || a && "object" == typeof a && "function" == typeof a.join && "function" == typeof a.reverse)
    for (var f = 0; f < a.length; f++) d(a[f]);
   else d(a);
   if (a = J[":" + e]) {
    c && !c.language && c.locale && (c.language = c.locale);
    c && "string" == typeof c.callback && (f = c.callback, f.match(/^[[\]A-Za-z0-9._]+$/) && (f =
     window.eval(f), c.callback = f));
    if ((f = c && null != c.callback) && !a.F(b)) throw G("Module: '" + e + "' must be loaded before DOM onLoad!");
    f ? a.v(b, c) ? window.setTimeout(c.callback, 0) : a.load(b, c) : a.v(b, c) || a.load(b, c)
   } else throw G("Module: '" + e + "' not found!");
  };
  m("google.load", google.load);
  google.ha = function(a, b) {
   b ? (0 == L.length && (M(window, "load", N), !B("msie") && !B("safari") && !B("konqueror") && B("mozilla") || window.opera ? window.addEventListener("DOMContentLoaded", N, !1) : B("msie") ? document.write("<script defer onreadystatechange='google.loader.domReady()' src=//:>\x3c/script>") : (B("safari") || B("konqueror")) && window.setTimeout(O, 10)), L.push(a)) : M(window, "load", a)
  };
  m("google.setOnLoadCallback", google.ha);

  function M(a, b, c) {
   if (a.addEventListener) a.addEventListener(b, c, !1);
   else if (a.attachEvent) a.attachEvent("on" + b, c);
   else {
    var d = a["on" + b];
    a["on" + b] = null != d ? ca([c, d]) : c
   }
  }

  function ca(a) {
   return function() {
    for (var b = 0; b < a.length; b++) a[b]()
   }
  }
  var L = [];
  google.loader.aa = function() {
   var a = window.event.srcElement;
   "complete" == a.readyState && (a.onreadystatechange = null, a.parentNode.removeChild(a), N())
  };
  m("google.loader.domReady", google.loader.aa);
  var da = {
   loaded: !0,
   complete: !0
  };

  function O() {
   da[document.readyState] ? N() : 0 < L.length && window.setTimeout(O, 10)
  }

  function N() {
   for (var a = 0; a < L.length; a++) L[a]();
   L.length = 0
  }
  google.loader.g = function(a, b, c) {
   if (null === l) b: {
    var d = h.document;
    if ((d = d.querySelector && d.querySelector("script[nonce]")) && (d = d.nonce || d.getAttribute("nonce")) && aa.test(d)) {
     l = d;
     break b
    }
    l = ""
   }
   d = l;
   if (c) {
    if ("script" == a) {
     var e = document.createElement("script");
     e.type = "text/javascript";
     e.src = b;
     d && e.setAttribute("nonce", d)
    } else "css" == a && (e = document.createElement("link"), e.type = "text/css", e.href = b, e.rel = "stylesheet");
    (a = document.getElementsByTagName("head")[0]) || (a = document.body.parentNode.appendChild(document.createElement("head")));
    a.appendChild(e)
   } else "script" == a ? (e = '<script src="' + A(b) + '" type="text/javascript"', d && (e += ' nonce="' + A(d) + '"'), document.write(e + ">\x3c/script>")) : "css" == a && (e = '<link href="' + A(b) + '" type="text/css" rel="stylesheet"', d && (e += ' nonce="' + A(d) + '"'), document.write(e + "></link>"))
  };
  m("google.loader.writeLoadTag", google.loader.g);
  google.loader.ea = function(a) {
   K = a
  };
  m("google.loader.rfm", google.loader.ea);
  google.loader.ga = function(a) {
   for (var b in a) "string" == typeof b && b && ":" == b.charAt(0) && !J[b] && (J[b] = new P(b.substring(1), a[b]))
  };
  m("google.loader.rpl", google.loader.ga);
  google.loader.fa = function(a) {
   if ((a = a.specs) && a.length)
    for (var b = 0; b < a.length; ++b) {
     var c = a[b];
     "string" == typeof c ? J[":" + c] = new Q(c) : (c = new R(c.name, c.baseSpec, c.customSpecs), J[":" + c.name] = c)
    }
  };
  m("google.loader.rm", google.loader.fa);
  google.loader.loaded = function(a) {
   J[":" + a.module].s(a)
  };
  m("google.loader.loaded", google.loader.loaded);
  google.loader.$ = function() {
   return "qid=" + ((new Date).getTime().toString(16) + Math.floor(1E7 * Math.random()).toString(16))
  };
  m("google.loader.createGuidArg_", google.loader.$);
  H("google_exportSymbol", H);
  H("google_exportProperty", I);
  google.loader.a = {};
  m("google.loader.themes", google.loader.a);
  google.loader.a.P = "//www.google.com/cse/static/style/look/bubblegum.css";
  n(google.loader.a, "BUBBLEGUM", google.loader.a.P);
  google.loader.a.S = "//www.google.com/cse/static/style/look/greensky.css";
  n(google.loader.a, "GREENSKY", google.loader.a.S);
  google.loader.a.R = "//www.google.com/cse/static/style/look/espresso.css";
  n(google.loader.a, "ESPRESSO", google.loader.a.R);
  google.loader.a.U = "//www.google.com/cse/static/style/look/shiny.css";
  n(google.loader.a, "SHINY", google.loader.a.U);
  google.loader.a.T = "//www.google.com/cse/static/style/look/minimalist.css";
  n(google.loader.a, "MINIMALIST", google.loader.a.T);
  google.loader.a.V = "//www.google.com/cse/static/style/look/v2/default.css";
  n(google.loader.a, "V2_DEFAULT", google.loader.a.V);

  function Q(a) {
   this.b = a;
   this.C = [];
   this.B = {};
   this.m = {};
   this.h = {};
   this.u = !0;
   this.f = -1
  }
  Q.prototype.j = function(a, b) {
   var c = "";
   void 0 != b && (void 0 != b.language && (c += "&hl=" + encodeURIComponent(b.language)), void 0 != b.nocss && (c += "&output=" + encodeURIComponent("nocss=" + b.nocss)), void 0 != b.nooldnames && (c += "&nooldnames=" + encodeURIComponent(b.nooldnames)), void 0 != b.packages && (c += "&packages=" + encodeURIComponent(b.packages)), null != b.callback && (c += "&async=2"), void 0 != b.style && (c += "&style=" + encodeURIComponent(b.style)), void 0 != b.noexp && (c += "&noexp=true"), void 0 != b.other_params && (c += "&" + b.other_params));
   if (!this.u) {
    google[this.b] && google[this.b].JSHash && (c += "&sig=" + encodeURIComponent(google[this.b].JSHash));
    b = [];
    for (var d in this.B) ":" == d.charAt(0) && b.push(d.substring(1));
    for (d in this.m) ":" == d.charAt(0) && this.m[d] && b.push(d.substring(1));
    c += "&have=" + encodeURIComponent(b.join(","))
   }
   return google.loader.ServiceBase + "/?file=" + this.b + "&v=" + a + google.loader.AdditionalParams + c
  };
  Q.prototype.I = function(a) {
   var b = null;
   a && (b = a.packages);
   var c = null;
   if (b)
    if ("string" == typeof b) c = [a.packages];
    else if (b.length)
    for (c = [], a = 0; a < b.length; a++) "string" == typeof b[a] && c.push(b[a].replace(/^\s*|\s*$/, "").toLowerCase());
   c || (c = ["default"]);
   b = [];
   for (a = 0; a < c.length; a++) this.B[":" + c[a]] || b.push(c[a]);
   return b
  };
  Q.prototype.load = function(a, b) {
   var c = this.I(b),
    d = b && null != b.callback;
   if (d) var e = new S(b.callback);
   for (var f = [], g = c.length - 1; 0 <= g; g--) {
    var k = c[g];
    d && e.W(k);
    this.m[":" + k] ? (c.splice(g, 1), d && this.h[":" + k].push(e)) : f.push(k)
   }
   if (c.length) {
    b && b.packages && (b.packages = c.sort().join(","));
    for (g = 0; g < f.length; g++) k = f[g], this.h[":" + k] = [], d && this.h[":" + k].push(e);
    if (b || null == K[":" + this.b] || null == K[":" + this.b].versions[":" + a] || google.loader.AdditionalParams || !this.u) b && b.autoloaded || google.loader.g("script", this.j(a,
     b), d);
    else {
     a = K[":" + this.b];
     google[this.b] = google[this.b] || {};
     for (var D in a.properties) D && ":" == D.charAt(0) && (google[this.b][D.substring(1)] = a.properties[D]);
     google.loader.g("script", google.loader.ServiceBase + a.path + a.js, d);
     a.css && google.loader.g("css", google.loader.ServiceBase + a.path + a.css, d)
    }
    this.u && (this.u = !1, this.f = (new Date).getTime(), 1 != this.f % 100 && (this.f = -1));
    for (g = 0; g < f.length; g++) k = f[g], this.m[":" + k] = !0
   }
  };
  Q.prototype.s = function(a) {
   -1 != this.f && (T("al_" + this.b, "jl." + ((new Date).getTime() - this.f), !0), this.f = -1);
   this.C = this.C.concat(a.components);
   google.loader[this.b] || (google.loader[this.b] = {});
   google.loader[this.b].packages = this.C.slice(0);
   for (var b = 0; b < a.components.length; b++) {
    this.B[":" + a.components[b]] = !0;
    this.m[":" + a.components[b]] = !1;
    var c = this.h[":" + a.components[b]];
    if (c) {
     for (var d = 0; d < c.length; d++) c[d].Z(a.components[b]);
     delete this.h[":" + a.components[b]]
    }
   }
  };
  Q.prototype.v = function(a, b) {
   return 0 == this.I(b).length
  };
  Q.prototype.F = function() {
   return !0
  };

  function S(a) {
   this.Y = a;
   this.w = {};
   this.D = 0
  }
  S.prototype.W = function(a) {
   this.D++;
   this.w[":" + a] = !0
  };
  S.prototype.Z = function(a) {
   this.w[":" + a] && (this.w[":" + a] = !1, this.D--, 0 == this.D && window.setTimeout(this.Y, 0))
  };

  function R(a, b, c) {
   this.name = a;
   this.X = b;
   this.A = c;
   this.H = this.l = !1;
   this.o = [];
   google.loader.G[this.name] = F(this.s, this)
  }
  E(R, Q);
  R.prototype.load = function(a, b) {
   var c = b && null != b.callback;
   c ? (this.o.push(b.callback), b.callback = "google.loader.callbacks." + this.name) : this.l = !0;
   b && b.autoloaded || google.loader.g("script", this.j(a, b), c)
  };
  R.prototype.v = function(a, b) {
   return b && null != b.callback ? this.H : this.l
  };
  R.prototype.s = function() {
   this.H = !0;
   for (var a = 0; a < this.o.length; a++) window.setTimeout(this.o[a], 0);
   this.o = []
  };
  var U = function(a, b) {
   return a.string ? encodeURIComponent(a.string) + "=" + encodeURIComponent(b) : a.regex ? b.replace(/(^.*$)/, a.regex) : ""
  };
  R.prototype.j = function(a, b) {
   return this.ba(this.J(a), a, b)
  };
  R.prototype.ba = function(a, b, c) {
   var d = "";
   a.key && (d += "&" + U(a.key, google.loader.ApiKey));
   a.version && (d += "&" + U(a.version, b));
   b = google.loader.Secure && a.ssl ? a.ssl : a.uri;
   if (null != c)
    for (var e in c) a.params[e] ? d += "&" + U(a.params[e], c[e]) : "other_params" == e ? d += "&" + c[e] : "base_domain" == e && (b = "http://" + c[e] + a.uri.substring(a.uri.indexOf("/", 7)));
   google[this.name] = {}; - 1 == b.indexOf("?") && d && (d = "?" + d.substring(1));
   return b + d
  };
  R.prototype.F = function(a) {
   return this.J(a).deferred
  };
  R.prototype.J = function(a) {
   if (this.A)
    for (var b = 0; b < this.A.length; ++b) {
     var c = this.A[b];
     if ((new RegExp(c.pattern)).test(a)) return c
    }
   return this.X
  };

  function P(a, b) {
   this.b = a;
   this.i = b;
   this.l = !1
  }
  E(P, Q);
  P.prototype.load = function(a, b) {
   this.l = !0;
   google.loader.g("script", this.j(a, b), !1)
  };
  P.prototype.v = function() {
   return this.l
  };
  P.prototype.s = function() {};
  P.prototype.j = function(a, b) {
   if (!this.i.versions[":" + a]) {
    if (this.i.aliases) {
     var c = this.i.aliases[":" + a];
     c && (a = c)
    }
    if (!this.i.versions[":" + a]) throw G("Module: '" + this.b + "' with version '" + a + "' not found!");
   }
   return google.loader.GoogleApisBase + "/libs/" + this.b + "/" + a + "/" + this.i.versions[":" + a][b && b.uncompressed ? "uncompressed" : "compressed"]
  };
  P.prototype.F = function() {
   return !1
  };
  var V = !1,
   W = [],
   ea = (new Date).getTime(),
   Y = function() {
    V || (M(window, "unload", X), V = !0)
   },
   fa = function(a, b) {
    Y();
    if (!(google.loader.Secure || google.loader.Options && !1 !== google.loader.Options.csi)) {
     for (var c = 0; c < a.length; c++) a[c] = encodeURIComponent(a[c].toLowerCase().replace(/[^a-z0-9_.]+/g, "_"));
     for (c = 0; c < b.length; c++) b[c] = encodeURIComponent(b[c].toLowerCase().replace(/[^a-z0-9_.]+/g, "_"));
     window.setTimeout(F(Z, null, "//gg.google.com/csi?s=uds&v=2&action=" + a.join(",") + "&it=" + b.join(",")), 1E4)
    }
   },
   T = function(a,
    b, c) {
    c ? fa([a], [b]) : (Y(), W.push("r" + W.length + "=" + encodeURIComponent(a + (b ? "|" + b : ""))), window.setTimeout(X, 5 < W.length ? 0 : 15E3))
   },
   X = function() {
    if (W.length) {
     var a = google.loader.ServiceBase;
     0 == a.indexOf("http:") && (a = a.replace(/^http:/, "https:"));
     Z(a + "/stats?" + W.join("&") + "&nc=" + (new Date).getTime() + "_" + ((new Date).getTime() - ea));
     W.length = 0
    }
   },
   Z = function(a) {
    var b = new Image,
     c = Z.da++;
    Z.K[c] = b;
    b.onload = b.onerror = function() {
     delete Z.K[c]
    };
    b.src = a;
    b = null
   };
  Z.K = {};
  Z.da = 0;
  H("google.loader.recordCsiStat", fa);
  H("google.loader.recordStat", T);
  H("google.loader.createImageForLogging", Z);

 })();
 google.loader.rm({
  "specs": ["visualization", "payments", {
   "name": "annotations",
   "baseSpec": {
    "uri": "http://www.google.com/reviews/scripts/annotations_bootstrap.js",
    "ssl": null,
    "key": {
     "string": "key"
    },
    "version": {
     "string": "v"
    },
    "deferred": true,
    "params": {
     "country": {
      "string": "gl"
     },
     "callback": {
      "string": "callback"
     },
     "language": {
      "string": "hl"
     }
    }
   }
  }, "language", "gdata", "wave", "spreadsheets", "search", "orkut", "feeds", "annotations_v2", "picker", "identitytoolkit", {
   "name": "maps",
   "baseSpec": {
    "uri": "http://maps.google.com/maps?file\u003dgoogleapi",
    "ssl": "https://maps-api-ssl.google.com/maps?file\u003dgoogleapi",
    "key": {
     "string": "key"
    },
    "version": {
     "string": "v"
    },
    "deferred": true,
    "params": {
     "callback": {
      "regex": "callback\u003d$1\u0026async\u003d2"
     },
     "language": {
      "string": "hl"
     }
    }
   },
   "customSpecs": [{
    "uri": "http://maps.googleapis.com/maps/api/js",
    "ssl": "https://maps.googleapis.com/maps/api/js",
    "version": {
     "string": "v"
    },
    "deferred": true,
    "params": {
     "callback": {
      "string": "callback"
     },
     "language": {
      "string": "hl"
     }
    },
    "pattern": "^(3|3..*)$"
   }]
  }, {
   "name": "friendconnect",
   "baseSpec": {
    "uri": "http://www.google.com/friendconnect/script/friendconnect.js",
    "ssl": "https://www.google.com/friendconnect/script/friendconnect.js",
    "key": {
     "string": "key"
    },
    "version": {
     "string": "v"
    },
    "deferred": false,
    "params": {}
   }
  }, {
   "name": "sharing",
   "baseSpec": {
    "uri": "http://www.google.com/s2/sharing/js",
    "ssl": null,
    "key": {
     "string": "key"
    },
    "version": {
     "string": "v"
    },
    "deferred": false,
    "params": {
     "language": {
      "string": "hl"
     }
    }
   }
  }, "ads", {
   "name": "books",
   "baseSpec": {
    "uri": "http://books.google.com/books/api.js",
    "ssl": "https://encrypted.google.com/books/api.js",
    "key": {
     "string": "key"
    },
    "version": {
     "string": "v"
    },
    "deferred": true,
    "params": {
     "callback": {
      "string": "callback"
     },
     "language": {
      "string": "hl"
     }
    }
   }
  }, "elements", "earth", "ima"]
 });
 google.loader.rfm({
  ":search": {
   "versions": {
    ":1": "1",
    ":1.0": "1"
   },
   "path": "/api/search/1.0/bb26211819c995bb58c0620c726c7b45/",
   "js": "default+en.I.js",
   "css": "default+en.css",
   "properties": {
    ":Version": "1.0",
    ":NoOldNames": false,
    ":JSHash": "bb26211819c995bb58c0620c726c7b45"
   }
  },
  ":language": {
   "versions": {
    ":1": "1",
    ":1.0": "1"
   },
   "path": "/api/language/1.0/69e2d4143fb2e4de590e5266894c5155/",
   "js": "default+en.I.js",
   "properties": {
    ":Version": "1.0",
    ":JSHash": "69e2d4143fb2e4de590e5266894c5155"
   }
  },
  ":annotations": {
   "versions": {
    ":1": "1",
    ":1.0": "1"
   },
   "path": "/api/annotations/1.0/3b0f18d6e7bf8cf053640179ef6d98d1/",
   "js": "default+en.I.js",
   "properties": {
    ":Version": "1.0",
    ":JSHash": "3b0f18d6e7bf8cf053640179ef6d98d1"
   }
  },
  ":wave": {
   "versions": {
    ":1": "1",
    ":1.0": "1"
   },
   "path": "/api/wave/1.0/3b6f7573ff78da6602dda5e09c9025bf/",
   "js": "default.I.js",
   "properties": {
    ":Version": "1.0",
    ":JSHash": "3b6f7573ff78da6602dda5e09c9025bf"
   }
  },
  ":ads": {
   "versions": {
    ":3": "1",
    ":3.0": "1"
   },
   "path": "/api/ads/3.0/551e6924d0b905f28dbc421713da34db/",
   "js": "default.I.js",
   "properties": {
    ":Version": "3.0",
    ":JSHash": "551e6924d0b905f28dbc421713da34db"
   }
  },
  ":picker": {
   "versions": {
    ":1": "1",
    ":1.0": "1"
   },
   "path": "/api/picker/1.0/1c635e91b9d0c082c660a42091913907/",
   "js": "default.I.js",
   "css": "default.css",
   "properties": {
    ":Version": "1.0",
    ":JSHash": "1c635e91b9d0c082c660a42091913907"
   }
  },
  ":ima": {
   "versions": {
    ":3": "1",
    ":3.0": "1"
   },
   "path": "/api/ima/3.0/28a914332232c9a8ac0ae8da68b1006e/",
   "js": "default.I.js",
   "properties": {
    ":Version": "3.0",
    ":JSHash": "28a914332232c9a8ac0ae8da68b1006e"
   }
  }
 });
 google.loader.rpl({
  ":swfobject": {
   "versions": {
    ":2.1": {
     "uncompressed": "swfobject_src.js",
     "compressed": "swfobject.js"
    },
    ":2.2": {
     "uncompressed": "swfobject_src.js",
     "compressed": "swfobject.js"
    }
   },
   "aliases": {
    ":2": "2.2"
   }
  },
  ":chrome-frame": {
   "versions": {
    ":1.0.0": {
     "uncompressed": "CFInstall.js",
     "compressed": "CFInstall.min.js"
    },
    ":1.0.1": {
     "uncompressed": "CFInstall.js",
     "compressed": "CFInstall.min.js"
    },
    ":1.0.2": {
     "uncompressed": "CFInstall.js",
     "compressed": "CFInstall.min.js"
    }
   },
   "aliases": {
    ":1": "1.0.2",
    ":1.0": "1.0.2"
   }
  },
  ":ext-core": {
   "versions": {
    ":3.1.0": {
     "uncompressed": "ext-core-debug.js",
     "compressed": "ext-core.js"
    },
    ":3.0.0": {
     "uncompressed": "ext-core-debug.js",
     "compressed": "ext-core.js"
    }
   },
   "aliases": {
    ":3": "3.1.0",
    ":3.0": "3.0.0",
    ":3.1": "3.1.0"
   }
  },
  ":webfont": {
   "versions": {
    ":1.0.12": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.13": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.14": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.15": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.10": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.11": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.27": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.28": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.29": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.23": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.24": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.25": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.26": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.21": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.22": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.3": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.4": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.5": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.6": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.9": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.16": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.17": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.0": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.18": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.1": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.19": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    },
    ":1.0.2": {
     "uncompressed": "webfont_debug.js",
     "compressed": "webfont.js"
    }
   },
   "aliases": {
    ":1": "1.0.29",
    ":1.0": "1.0.29"
   }
  },
  ":scriptaculous": {
   "versions": {
    ":1.8.3": {
     "uncompressed": "scriptaculous.js",
     "compressed": "scriptaculous.js"
    },
    ":1.9.0": {
     "uncompressed": "scriptaculous.js",
     "compressed": "scriptaculous.js"
    },
    ":1.8.1": {
     "uncompressed": "scriptaculous.js",
     "compressed": "scriptaculous.js"
    },
    ":1.8.2": {
     "uncompressed": "scriptaculous.js",
     "compressed": "scriptaculous.js"
    }
   },
   "aliases": {
    ":1": "1.9.0",
    ":1.8": "1.8.3",
    ":1.9": "1.9.0"
   }
  },
  ":mootools": {
   "versions": {
    ":1.3.0": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.2.1": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.1.2": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.4.0": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.3.1": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.2.2": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.4.1": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.3.2": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.2.3": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.4.2": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.2.4": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.2.5": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    },
    ":1.1.1": {
     "uncompressed": "mootools.js",
     "compressed": "mootools-yui-compressed.js"
    }
   },
   "aliases": {
    ":1": "1.1.2",
    ":1.1": "1.1.2",
    ":1.2": "1.2.5",
    ":1.3": "1.3.2",
    ":1.4": "1.4.2",
    ":1.11": "1.1.1"
   }
  },
  ":jqueryui": {
   "versions": {
    ":1.8.17": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.16": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.15": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.14": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.4": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.13": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.5": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.12": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.6": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.11": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.7": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.10": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.8": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.9": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.6.0": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.7.0": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.5.2": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.0": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.7.1": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.5.3": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.1": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.7.2": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.8.2": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    },
    ":1.7.3": {
     "uncompressed": "jquery-ui.js",
     "compressed": "jquery-ui.min.js"
    }
   },
   "aliases": {
    ":1": "1.8.17",
    ":1.8.3": "1.8.4",
    ":1.5": "1.5.3",
    ":1.6": "1.6.0",
    ":1.7": "1.7.3",
    ":1.8": "1.8.17"
   }
  },
  ":yui": {
   "versions": {
    ":2.8.0r4": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    },
    ":2.9.0": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    },
    ":2.8.1": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    },
    ":2.6.0": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    },
    ":2.7.0": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    },
    ":3.3.0": {
     "uncompressed": "build/yui/yui.js",
     "compressed": "build/yui/yui-min.js"
    },
    ":2.8.2r1": {
     "uncompressed": "build/yuiloader/yuiloader.js",
     "compressed": "build/yuiloader/yuiloader-min.js"
    }
   },
   "aliases": {
    ":2": "2.9.0",
    ":3": "3.3.0",
    ":2.8.2": "2.8.2r1",
    ":2.8.0": "2.8.0r4",
    ":3.3": "3.3.0",
    ":2.6": "2.6.0",
    ":2.7": "2.7.0",
    ":2.8": "2.8.2r1",
    ":2.9": "2.9.0"
   }
  },
  ":prototype": {
   "versions": {
    ":1.6.1.0": {
     "uncompressed": "prototype.js",
     "compressed": "prototype.js"
    },
    ":1.6.0.2": {
     "uncompressed": "prototype.js",
     "compressed": "prototype.js"
    },
    ":1.7.0.0": {
     "uncompressed": "prototype.js",
     "compressed": "prototype.js"
    },
    ":1.6.0.3": {
     "uncompressed": "prototype.js",
     "compressed": "prototype.js"
    }
   },
   "aliases": {
    ":1": "1.7.0.0",
    ":1.6.0": "1.6.0.3",
    ":1.6.1": "1.6.1.0",
    ":1.7.0": "1.7.0.0",
    ":1.6": "1.6.1.0",
    ":1.7": "1.7.0.0"
   }
  },
  ":jquery": {
   "versions": {
    ":1.3.0": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.4.0": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.3.1": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.5.0": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.4.1": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.3.2": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.2.3": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.6.0": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.5.1": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.4.2": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.7.0": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.6.1": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.5.2": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.4.3": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.7.1": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.6.2": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.4.4": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.2.6": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.6.3": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    },
    ":1.6.4": {
     "uncompressed": "jquery.js",
     "compressed": "jquery.min.js"
    }
   },
   "aliases": {
    ":1": "1.7.1",
    ":1.2": "1.2.6",
    ":1.3": "1.3.2",
    ":1.4": "1.4.4",
    ":1.5": "1.5.2",
    ":1.6": "1.6.4",
    ":1.7": "1.7.1"
   }
  },
  ":dojo": {
   "versions": {
    ":1.3.0": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.4.0": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.3.1": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.5.0": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.4.1": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.3.2": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.2.3": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.6.0": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.5.1": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.7.0": {
     "uncompressed": "dojo/dojo.js.uncompressed.js",
     "compressed": "dojo/dojo.js"
    },
    ":1.6.1": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.4.3": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.7.1": {
     "uncompressed": "dojo/dojo.js.uncompressed.js",
     "compressed": "dojo/dojo.js"
    },
    ":1.7.2": {
     "uncompressed": "dojo/dojo.js.uncompressed.js",
     "compressed": "dojo/dojo.js"
    },
    ":1.2.0": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    },
    ":1.1.1": {
     "uncompressed": "dojo/dojo.xd.js.uncompressed.js",
     "compressed": "dojo/dojo.xd.js"
    }
   },
   "aliases": {
    ":1": "1.6.1",
    ":1.1": "1.1.1",
    ":1.2": "1.2.3",
    ":1.3": "1.3.2",
    ":1.4": "1.4.3",
    ":1.5": "1.5.1",
    ":1.6": "1.6.1",
    ":1.7": "1.7.2"
   }
  }
 });
}
google.load("visualization", "1.1", {
 "autoloaded": true,
 "packages": ["corechart"]
});
if (window['google'] != undefined && window['google']['loader'] != undefined) {
 if (!window['google']['visualization']) {
  window['google']['visualization'] = {};
  google.visualization.Version = '1.1';
  google.visualization.JSHash = '760702f7b56bd967c887f2c9118815e6';
  google.visualization.LoadArgs = 'file\x3dvisualization\x26v\x3d1.1\x26packages\x3dcorechart';
 }
 google.loader.writeLoadTag("css", google.loader.ServiceBase + "/api/visualization/1.1/760702f7b56bd967c887f2c9118815e6/ui+en.css", false);
 google.loader.writeLoadTag("script", google.loader.ServiceBase + "/api/visualization/1.1/760702f7b56bd967c887f2c9118815e6/webfontloader,format+en,default+en,ui+en,corechart+en.I.js", false);
}