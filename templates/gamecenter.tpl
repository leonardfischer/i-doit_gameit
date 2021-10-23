<div id="gameIT">
    <h1>[{isys type="lang" ident="LC__MODULE__GAMEIT__LEADERBOARD"}]</h1>

    <div id="gameCenterContainer">
	    <ul id="game-tabs" class="browser-tabs m0 gradient">
		    <li><a href="#top-three">[{isys type="lang" ident="LC__MODULE__GAMEIT__TOP_THREE"}]</a></li>
		    <li><a href="#progress-over-time">[{isys type="lang" ident="LC__MODULE__GAMEIT__PROGRESS_OVER_TIME"}]</a></li>
		    <li><a href="#top-ten">[{isys type="lang" ident="LC__MODULE__GAMEIT__TOP_TEN"}]</a></li>
	    </ul>

	    <div class="p20">
		    <div id="top-three">
			    <h2>[{isys type="lang" ident="LC__MODULE__GAMEIT__TOP_THREE"}]</h2>

		        <table class="three-col border-bottom text-center w100" style="height: 300px; border-spacing: 0; border-collapse: separate;">
		            <tr>
		                [{foreach $topThree as $top}]
		                <td class="vab">
		                    <strong>[{$top.objectTitle}]</strong>
		                    <div class="top-bar" style="background:[{$top.color}]; height:[{($top.points / $topScore)*250}]px;">
		                        <strong>[{$top.points}]</strong>
		                    </div>
		                </td>
		                [{/foreach}]
		            </tr>
		        </table>
		    </div>

			<div id="progress-over-time" style="display:none;">
		        <h2>[{isys type="lang" ident="LC__MODULE__GAMEIT__PROGRESS_OVER_TIME"}]</h2>

		        <div class="svg">
		            <svg class="w100"></svg>
		        </div>
			</div>

		    <div id="top-ten" style="display:none;">
		        <h2>[{isys type="lang" ident="LC__MODULE__GAMEIT__TOP_TEN"}]</h2>

		        <table class="w100 listing">
		            <thead>
		            <tr>
		                <th>[{isys type="lang" ident="LC__MODULE__GAMEIT__PLAYERS"}]</th>
		                <th>[{isys type="lang" ident="LC__MODULE__GAMEIT__POINTS"}]</th>
		            </tr>
		            </thead>
		            <tbody>
		            [{foreach $topTen as $top}]
		                <tr>
		                    <td>[{$top.objectTitle}]</td>
		                    <td>[{$top.points}]</td>
		                </tr>
		                [{/foreach}]
		            </tbody>
		        </table>
		    </div>
	    </div>
    </div>
</div>

<style type="text/css">
    #navBar, #infoBox, #draggableBar {
        display: none;
    }

    #contentWrapper {
        top: 35px;
        bottom: 0;
    }

    #gameIT {
        background-color: #1b80b1;
        padding: 20px;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
    }

    #gameIT h1 {
        color: #fff;
        padding-top: 30px;
        text-align: center;
        font-size: 35pt;
    }

    #gameIT h2 {
        padding-top: 30px;
        text-align: center;
        font-size: 30pt;
    }

    #gameCenterContainer {
        position: relative;
        max-width: 800px;
        width: 100%;
        margin: 20px auto;
        font-size: 11pt;
        background: #fff;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .5);
    }

    #gameCenterContainer .top-bar {
        width: 80%;
        margin-left: 10%;
        background: #f80;
        border-top: 2px solid rgba(0, 0, 0, .3);
    }

    #gameCenterContainer .top-bar strong {
        color: #fff;
        font-size: 20pt;
    }

    #gameCenterContainer svg {
        height: 300px;
    }

    #gameCenterContainer svg path.line,
    #gameCenterContainer svg circle {
        fill: none;
        stroke-width: 2px;
    }

    #gameCenterContainer svg circle {
        fill: #fff;
    }
</style>

<script>
    (function () {
        'use strict';

        var $canvas         = $('gameCenterContainer').down('.svg'),
            topThree        = JSON.parse('[{$topThree|json_encode|escape:"javascript"}]'),
            topThreeDetails = JSON.parse('[{$topThreeData|json_encode|escape:"javascript"}]');

        idoit.Require.require(['d3'], function () {
            var i, j, sumPoints;

            for (i in topThreeDetails) {
                if (!topThreeDetails.hasOwnProperty(i)) {
                    continue;
                }

                sumPoints = 0;

                for (j in topThreeDetails[i]) {
                    if (!topThreeDetails[i].hasOwnProperty(j)) {
                        continue;
                    }

                    topThreeDetails[i][j].eventDate = new Date(topThreeDetails[i][j].eventDate);
                    topThreeDetails[i][j].points = sumPoints = (sumPoints + topThreeDetails[i][j].points);
                }
            }

            function redraw() {
                var svg    = d3.select($canvas.down('svg')).html(""),
                    margin = {
                        top:    20,
                        right:  20,
                        bottom: 30,
                        left:   50
                    },
                    width  = svg.node().getBoundingClientRect().width - margin.left - margin.right,
                    height = 300 - margin.top - margin.bottom,
                    g      = svg.append("g").attr("transform", "translate(" + margin.left + "," + margin.top + ")"),
                    sumPoints;

                var x = d3.scaleTime()
                    .range([0, width])
                    .domain([new Date('[{$firstDate}]'), new Date('[{$lastDate}]')]);

                var y = d3.scaleLinear()
                    .range([height, 0])
                    .domain([0, parseInt('[{$topScore}]')]);

                var line = d3.line()
                    .x(function (d) {
                        return x(d.eventDate);
                    })
                    .y(function (d) {
                        return y(d.points);
                    })
                    .curve(d3.curveMonotoneX);

                g.append("g")
                    .attr("transform", "translate(0," + height + ")")
                    .call(d3.axisBottom(x))
                    .selectAll('path,line').style('stroke', '#aaa');

                g.append("g")
                    .call(d3.axisLeft(y))
                    .selectAll('path,line').style('stroke', '#aaa');

                for (i in topThreeDetails) {
                    if (!topThreeDetails.hasOwnProperty(i)) {
                        continue;
                    }

                    sumPoints = 0;

                    g.append("path")
                        .datum(topThreeDetails[i])
                        .attr('class', 'line')
                        .attr('stroke', function (d) {
                            return topThree['o' + d[0].objectId].color;
                        })
                        .attr('d', line);

                    g.selectAll('circle.c' + i)
                        .data(topThreeDetails[i])
                        .enter()
                        .append('circle')
                        .attr('class', 'c' + i)
                        .attr('r', 3)
                        .attr('stroke', function (d) {
                            return topThree['o' + d.objectId].color;
                        })
                        .attr('transform', function (d) {
                            return 'translate(' + x(d.eventDate) + ',' + y(d.points) + ')';
                        });
                }
            }

            Event.observe(window, 'resize', function () {
                // Update the SVG!
                redraw();
            });

            new Tabs('game-tabs', {
                wrapperClass: 'browser-tabs',
                contentClass: '',
                tabClass:     'text-shadow mouse-pointer',
                onTabSelect:  function () {
                    // This needs to happen because the initial render will display a canvas with 0x0.
                    redraw();
                }
            });
        });
    })();
</script>
