<!DOCTYPE html>
<html>
<?=$header?>
<body>
<div id="container">
    <header>
        <!--<h1>&lt;GoodReads.by /&gt;</h1>-->
        <?=$top?>
        <a href="/"><img src="/images/logo.png" alt="goodreads"/></a>
    </header>
    <div id="top">
        <div class="clear"></div>
        <div id="search">
            <form name="search" action="<?=$link_search?>" method="get">
                <table>
                    <tr>
                        <td>
                            <input type="text" name="query" placeholder="Поиск"/>
                        </td>
                        <td>
                            <input type="submit" value=""/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <?=$auth?>
    </div>
    <?=$slider?>
    <div id="content">
        <div id="left"><?=$left?></div>
        <div id="right"><?=$right?></div>
        <div id="center"><?=$center?></div>
        <div class="clear"></div>
    </div>
    <footer>
        <div class="sep"></div>
        <!-- Yandex.Metrika counter -->
        <div style="display:none;">
            <script type="text/javascript">
                (function (w, c) {
                    (w[c] = w[c] || []).push(function () {
                        try {
                            w.yaCounter3932665 = new Ya.Metrika(3932665);
                            yaCounter3932665.clickmap(true);
                            yaCounter3932665.trackLinks(true);
                        } catch (e) {
                        }
                    });
                })(window, 'yandex_metrika_callbacks');
            </script>
        </div>
        <script src="/mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
        <noscript>
            <div style="position:absolute">
                <img src="/mc.yandex.ru/watch/3932665" alt=""/>
            </div>
        </noscript>
        <!-- /Yandex.Metrika counter -->
        <!--LiveInternet counter-->
        <script type="text/javascript">
            document.write("<a href='//www.liveinternet.ru/click' " +
                "target=_blank><img src='//counter.yadro.ru/hit?t21.11;r" +
                escape(document.referrer) + ((typeof(screen) == "undefined") ? "" :
                    ";s" + screen.width + "*" + screen.height + "*" + (screen.colorDepth ?
                    screen.colorDepth : screen.pixelDepth)) + ";u" + escape(document.URL) +
                ";h" + escape(document.title.substring(0, 150)) + ";" + Math.random() +
                "' alt='' title='LiveInternet: показано число просмотров за 24" +
                " часа, посетителей за 24 часа и за сегодня' " +
                "border='0' width='88' height='31'><\/a>")
        </script><!--/LiveInternet-->
        <!-- Rating@Mail.ru counter -->
        <script type="text/javascript">
            var _tmr = window._tmr || (window._tmr = []);
            _tmr.push({id: "3028855", type: "pageView", start: (new Date()).getTime()});
            (function (d, w, id) {
                if (d.getElementById(id)) return;
                var ts = d.createElement("script");
                ts.type = "text/javascript";
                ts.async = true;
                ts.id = id;
                ts.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//top-fwz1.mail.ru/js/code.js";
                var f = function () {var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(ts, s);};
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "topmailru-code");
        </script>
        <noscript>
            <div>
                <img src="//top-fwz1.mail.ru/counter?id=3028855;js=na" style="border:0;position:absolute;left:-9999px;" alt=""/>
            </div>
        </noscript>
        <!-- //Rating@Mail.ru counter -->
        <!-- Rating@Mail.ru logo -->
        <a href="https://top.mail.ru/jump?from=3028855">
            <img src="//top-fwz1.mail.ru/counter?id=3028855;t=479;l=1"
                 style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru"/></a>
        <!-- //Rating@Mail.ru logo -->
        <!-- begin of Top100 code -->
        <!-- Top100 (Kraken) Widget -->
        <span id="top100_widget"></span>
        <!-- END Top100 (Kraken) Widget -->
        <!-- Top100 (Kraken) Counter -->
        <script>
            (function (w, d, c) {
                (w[c] = w[c] || []).push(function () {
                    var options = {
                        project: 6173392,
                        element: 'top100_widget',
                    };
                    try {
                        w.top100Counter = new top100(options);
                    } catch (e) {
                    }
                });
                var n = d.getElementsByTagName("script")[0],
                    s = d.createElement("script"),
                    f = function () {
                        n.parentNode.insertBefore(s, n);
                    };
                s.type = "text/javascript";
                s.async = true;
                s.src =
                    (d.location.protocol == "https:" ? "https:" : "http:") +
                    "//st.top100.ru/top100/top100.js";

                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(window, document, "_top100q");
        </script>
        <noscript>
            <img src="//counter.rambler.ru/top100.cnt?pid=6173392" alt="Топ-100"/>
        </noscript>
        <!-- END Top100 (Kraken) Counter -->
        <p>Copyright &copy; 2017-<?=date("Y")?> Фиц Андрей Владимирович. Все права защищены.</p>
    </footer>
</div>
</body>
</html>