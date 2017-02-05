<!DOCTYPE html>
<html lang="en">

<head>
	<title>3D Model Viewer</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style/style.css" />
</head>

<body onload="onLoad();">
	<div id="container">
		<select onchange="switchModel(this.value,this.getAttribute('data-loc'))" class="button" id="modelselec">
			<?php
                $dir = 'obj/';
                $files = array_diff(scandir($dir), array('..', '.','uploads'));
                foreach ($files as $model) {
                    echo '<option data-loc="'.$dir.'">'.$model.'</option>';
                }
				$dir = 'obj/uploads/';
				$files = array_diff(scandir($dir), array_merge(array('..', '.'),$files));
				foreach ($files as $model) {
					echo '<option data-loc="'.$dir.'">'.$model.'</option>';
				}
			?>
		</select>
		
		<select onchange="switchTex(this.value,this.getAttribute('data-loc'))" class="button" id="texselec">
			<?php
				$dir = 'textures/';
                $files = array_diff(scandir($dir), array('..', '.','uploads'));
                foreach ($files as $text) {
                    echo '<option data-loc="'.$dir.'">'.$text.'</option>';
                }
				$dir = 'textures/uploads/';
				$files = array_diff(scandir($dir), array_merge(array('..', '.'),$files));
				foreach ($files as $text) {
					echo '<option data-loc="'.$dir.'">'.$text.'</option>';
				}
			?>
		</select>

		<img src="img/maximize.svg" id="fullscreen" class="button" />
	</div>
	<div id="upload_form">
		<form enctype="multipart/form-data" action="upload.php" method="post">
			<!-- MAX_FILE_SIZE doit précéder le champ input de type file -->
			<input type="hidden" name="MAX_FILE_SIZE" value="1048576000" />
			<label for="model">3D Model :</label>
			<!-- Le nom de l'élément input détermine le nom dans le tableau $_FILES -->
			<input name="file[]" type="file" id="model" />
			<label for="texture">Texture :</label>
			<input name="file[]" type="file" id="texture" />
			<input type="submit" value="Envoyer le fichier" />
		</form>
	</div>
	<script src="js/three.min.js"></script>

	<script src="js/loaders/OBJLoader.js"></script>

	<script src="js/controls/TrackballControls.js"></script>

	<script src="js/Detector.js"></script>
	<script src="js/libs/stats.min.js"></script>
	<script src='js/threex.basiclighting/threex.basiclighting.js'></script>
	<script src='js/objects.choose.js'></script>

	<script type="text/javascript">
		var canvWidth = 1280;
		var canvHeight = 720;
		var modelLoc = "obj/dino02.obj";
		var TexLoc = "textures/diffuse02.jpg";
		var model = new THREEg.Model(modelLoc, TexLoc);

        document.querySelector("#modelselec").addEventListener("change",function(){
            scene.remove(model);
            modelLoc = this.options[this.selectedIndex].getAttribute('data-loc') + this.value;
            model = new THREEg.Model(modelLoc, TexLoc);
            scene.add(model);
        });

        document.querySelector("#texselec").addEventListener("change",function(){
            scene.remove(model);
            console.log(this);
            console.log(this.selected);
            TexLoc = this.options[this.selectedIndex].getAttribute('data-loc') + this.value;
            model = new THREEg.Model(modelLoc, TexLoc);
            scene.add(model)
        });

		function switchModel(name,loc) {

		}

		function switchTex(name,loc) {
			scene.remove(model);
			TexLoc = loc + name;
			model = new THREEg.Model(modelLoc, TexLoc);
			scene.add(model)
		}

		function initScene() {
			var container = document.getElementById("container");

			renderer = new THREE.WebGLRenderer({
				antialias: true
			});
			renderer.setSize(canvWidth, canvHeight);
			renderer.setClearColor(0x0f0f0f, 1);
			container.appendChild(renderer.domElement);

			scene = new THREE.Scene();

			camera = new THREE.PerspectiveCamera(75, canvWidth / canvHeight, 1, 5000);
			camera.position.z = 1000;

			controls = new THREE.TrackballControls(camera, renderer.domElement);
			controls.rotateSpeed = 8.0;
			controls.zoomSpeed = 2.0;
			controls.panSpeed = 0.2;

			controls.noZoom = false;
			controls.noPan = false;

			controls.staticMoving = false;
			controls.dynamicDampingFactor = 0.3;

			controls.minDistance = 500;
			controls.maxDistance = 3000;

			controls.keys = [16, 17, 18]; // [ rotateKey, zoomKey, panKey ]

			//lights

			var lighting = new THREEx.ThreePointsLighting();
			scene.add(lighting);

			animate();

			function animate() {
				requestAnimationFrame(animate);
				render();
			}

			function render() {
				controls.update(); //for cameras
				renderer.render(scene, camera);
			}
		}

		function onLoad() {
			initScene();
			addObjects();
		}

		var c = document.getElementsByTagName("canvas");

		function full() {
			var el = document.getElementById("container");
			var btn = document.getElementById("fullscreen");

			if (!document.mozFullScreen && !document.webkitIsFullScreen) {
				if (el.requestFullscreen) {
					el.requestFullscreen();
					btn.src = "img/minimize.svg";
				} else if (el.mozRequestFullScreen) {
					el.mozRequestFullScreen();
					btn.src = "img/minimize.svg";
				} else if (el.webkitRequestFullscreen) {
					el.webkitRequestFullscreen();
					btn.src = "img/minimize.svg";
				}
			} else {
				if (document.exitFullScreen) {
					document.exitFullScreen();
					btn.src = "img/maximize.svg";
				} else if (document.mozCancelFullScreen) {
					document.mozCancelFullScreen();
					btn.src = "img/maximize.svg";
				} else if (document.webkitCancelFullScreen) {
					document.webkitCancelFullScreen();
					btn.src = "img/maximize.svg";
				}
			}
		}

		function resize() {
			if (document.mozFullScreen || document.webkitIsFullScreen) {
				renderer.setSize(window.innerWidth, window.innerHeight);
				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();
			} else {
				renderer.setSize(canvWidth, canvHeight);
				camera.aspect = canvWidth / canvHeight;
				camera.updateProjectionMatrix();
			}
		}
		document.getElementById("fullscreen").addEventListener("click", full);
		document.addEventListener('mozfullscreenchange', resize);
		document.addEventListener('webkitfullscreenchange', resize);

		function addObjects() {
			scene.add(camera);
			//			scene.add(lighting);
			scene.add(model);
		}
	</script>
</body>

</html>