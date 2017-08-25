<?php
$id = @$_GET['id'] ?: '0Bmhjf0rKe8';
$start = @$_GET['start'] ?: 0;
$end = @$_GET['end'] ?: 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8" />
	<title>YT <<< Overflow</title>

	<style>
	* {
		box-sizing: border-box;
	}

	html, body {
		margin: 0;
		padding: 0;
	}

	#app {
		width: 100vw;
		height: 100vh;
		overflow: hidden;
		background: #000;
	}
		#app:after {
			opacity: .5;
			z-index: 1;
			content: '';
			position: fixed;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			border-radius: 2%;
			background: linear-gradient(to right, #ff0004 0%,#ff0004 25%,#2eff00 25%,#2eff00 25%,#2eff00 50%,#2eff00 75%,#1500ff 75%,#1500ff 100%) repeat;
			background-size: 7px;
		}

		@keyframes vline {
			from {
				opacity: 0;
				top: 0%;
			}

			50% {
				opacity: 1;
				top: 20%;
			}

			to {
				opacity: 0;
				top: 80%;
			}
		}

		#app:before {
			z-index: 3;
			content: '';
			position: absolute;
			top: 0%;
			left: 0;
			right: 0;
			height: 9px;
			mix-blend-mode: overlay;
			animation: vline 10s linear infinite;
			background: linear-gradient(to bottom, transparent, snow 50%, rgba(255, 255, 255, .98) 51%, transparent) repeat;
		}

	#app div:first-child {
		width: 100%;
		height: 100%;
		margin: 0;
		overflow: hidden;
		outline: 1px solid blue;
	}

	#app div:first-child iframe {
		z-index: 2;
		position: absolute;
		top: 0;
		right: 0;
		bottom: 0;
		left: 0;
		min-width: 100%;
		min-height: 100%;
		margin: 0;
		padding: 0;
		border: 0;
		mix-blend-mode: hard-light;
	}

	#app div:not(:first-child) {
		display: none;
	}

	count {
		z-index: 5;
		position: fixed;
		top: 15px;
		left: 15px;
		color: #FFF;
		font-size: 10vmin;
	}
	</style>
</head>
<body>

<div id="app"></div>

<script>
// Load the IFrame Player API code asynchronously.
var tag = document.createElement('script');
tag.src = 'https://www.youtube.com/player_api';
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
</script>

<script>
const
	max = 50,
	delay = <?=$end - $start?> * 1000,

	$count = document.body.appendChild(document.createElement('count')),
	getCount = () => parseInt($count.innerText, 10) || 0,

	createPlayer = (id, cb) => {
 		const player = new YT.Player(id, {
			videoId: '<?=$id?>',
			width: 0,
			height: 0,
			playerVars: {
				autoplay: 1,
				controls: 0,
				showinfo: 0,
				fs: 1,
				modestbranding: 1,
				cc_load_policy: 0,
				iv_load_policy: 3,
				start: <?=$start?>,
				end: <?=$end?>,
				autohide: 0,
				rel: 0,
			},
			events: {
				onReady: () => {
					$count.innerHTML = (getCount() || 0) + 1
				},
				onStateChange: ({ data }) => {
					if (data === YT.PlayerState.ENDED)
						player.loadVideoById({
							videoId: '<?=$id?>',
							startSeconds: <?=$start?>,
							endSeconds: <?=$end?>,
						})

					if (cb) cb(data)
				}
			}
		})

		return player
	},

	addVideo = () => {
		if (getCount() > max)
			return

		const
			$div = document.createElement('div'),
			$tmp = document.createElement('div')

		$tmp.id = Math.random().toString(36).substr(2, 10)

		$div.appendChild($tmp)
		app.appendChild($div)

		return $tmp
	}

window.onload = () => createPlayer(addVideo().id, data => {
	if (!document.body.classList.contains('seen') && data === YT.PlayerState.ENDED) {
		document.body.classList.add('seen')
	} else if (data === YT.PlayerState.PLAYING) {
		for (let i = 0; i <= Math.round(getCount()); i++) {
			createPlayer(addVideo().id)
		}
	}
})
</script>

</body>
</html>
