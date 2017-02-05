/*
 * Created by : Guillaume Noailles
 * Date : 2015
 */

var THREEg = THREEg || {};


THREEg.Model = function (Loc, TexLoc) {

		var manager = new THREE.LoadingManager();
		manager.onProgress = function (item, loaded, total) {
			console.log(item, loaded, total);
		};


		var texture = new THREE.Texture();

		var onProgress = function (xhr) {
			if (xhr.lengthComputable) {
				var percentComplete = xhr.loaded / xhr.total * 100;
				console.log(Math.round(percentComplete, 2) + '% downloaded');
			}
		};

		var onError = function (xhr) {};

		var loader = new THREE.ImageLoader(manager);
		loader.load(TexLoc, function (image) {
			texture.image = image;
			texture.needsUpdate = true;
		});


		var content = new THREE.Object3D();
		loader = new THREE.OBJLoader(manager);
		loader.load(Loc, function (object) {
			object.traverse(function (child) {

				if (child instanceof THREE.Mesh) {

					child.material.map = texture;

				}

			});

			object.position.y = -350;
			object.position.x = 0;
			object.position.z = 0;
			content.add(object);
		}, onProgress, onError);

		return manager, texture, onProgress, onError, loader, content;
	};