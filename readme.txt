=== Stock market charts from finviz ===
Contributors: morisdov
Donate link: http://www.childrensheartlink.org/donate
Tags: stock, market, chart, ticker, finance
Requires at least: 3.6
Tested up to: 6.4
Stable tag: trunk
License: GPLv2

Embed dynamic stock market charts from finviz.com

== Description ==

Shortcode `[finviz ticker=GE]` embeds stock market chart from [finviz.com](https://finviz.com) financial visualizations
Charts are dynamically refreshed on each page load

> `[finviz ticker=GE]` embeds [chart](https://www.finviz.com/chart.ashx?t=GE&ty=c&ta=1&p=d&s=l) of [General Electric](https://finviz.com/quote.ashx?t=GE)

Option to <strong>hyperlink</strong> chart to finviz site ticker page
Option to <strong>lazy load</strong> chart images
Option to specify chart image <strong>alt</strong> attribute text prefix
Option to specify chart image <strong>width</strong> `[finviz ticker=GE width=500]`
Option to specify chart <strong>type</strong> [line](https://charts2.finviz.com/chart.ashx?t=GE&ty=l&ta=1&p=d&s=l) or [candle](https://charts2.finviz.com/chart.ashx?t=GE&ty=c&ta=1&p=d&s=l)
Option to specify chart <strong>time period</strong> [day](https://charts2.finviz.com/chart.ashx?t=GE&ty=c&p=d&s=l), [week](https://charts2.finviz.com/chart.ashx?t=GE&ty=c&p=w&s=l) or [month](https://charts2.finviz.com/chart.ashx?t=GE&ty=c&p=m&s=l)
Option to add [trailing averages](https://charts2.finviz.com/chart.ashx?t=GE&ty=c&ta=1&p=d&s=l) to only `daily` period chart


Chart images include small branded logo of finviz.com
Please review the [privacy policy](https://www.finviz.com/privacy.ashx) of finviz.com

No API key or registration required.

== Frequently Asked Questions ==
= Are the charts real time ? =
Free charts for non-subscribers are delayed by 15 minutes for NASDAQ, and 20 minutes for NYSE and AMEX
= Can i style the charts with my custom CSS ? =
Yes, add to your theme custom styling with class names `finviz-anchor` and `finviz-image` 
> screenshot
= Are individual Crypto currency charts embeddable ? =
No, alternatively link to the [Crypto charts](https://www.finviz.com/crypto.ashx) page url
or, link to selected [Crypto currency](https://www.finviz.com/crypto_charts.ashx?p=d1&t=BTCUSD) page url

== Screenshots ==
1. Shortcode example
2. Plugin Settings
3. Custom CSS styling option with class names 'finviz-anchor' and 'finviz-image'

== Changelog ==
= 1.0 =
* First release

= 1.0.1 =
* Minor code update, No new features

= 1.0.2 =
* lazy load images, added chart optionts

== Upgrade Notice ==
* Minor code update