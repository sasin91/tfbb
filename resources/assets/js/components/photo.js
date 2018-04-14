import Pica from 'pica'

export default class Photo {
	constructor (photo, proportions) {
		this.photo = photo;
		this.canvas = document.createElement('canvas');
		this.manipulations = {};
		this.proportions = Object.assign({}, proportions, this.defaultProportions());
	}

	defaultProportions () {
		return {
			maxHeight: 4096,
			maxWidth: 2160,
			minHeight: 240,
			minWidth: 320
		}
	}

	initializeImage () {
		return new Promise((resolve, reject) => {
			const fileReader = new FileReader;
			fileReader.readAsDataURL(this.photo);

			fileReader.onload = () => {
				const image = new Image;

				image.src = fileReader.result;

				resolve(image);
			}

			fileReader.onerror = error => reject(error);
		});
	}

	addManipulations (manipulations) {
		this.manipulations = Object.assign({}, manipulations, this.manipulations);

		return this;
	}

	prepareForUpload () {
		return this.runManipulations();
	}

	runManipulations () {
		return new Promise((resolve, reject) => {
			this.initializeImage().then(image => {
				this.image = image;
				this.computeSizeManipulations();

				const pica = new Pica;

				pica.resize(this.image, this.canvas, this.manipulations)
					.then(result => {
						pica.toBlob(result, 'image/png', 0.90)
							.then(blob => resolve({
									photo: blob,
									preview: this.image.src
								})
							)
					})
					.catch(errors => reject(errors));
			});
		});
	}

	computeSizeManipulations () {
		this.computeCanvasSize();

		this.downsizeIfNeeded();
		this.upsizeIfNeeded();
	}

	computeCanvasSize () {
    	const ratio = Math.min(
    		this.proportions.maxWidth / this.image.width,
    		this.proportions.maxHeight / this.image.height
    	);

    	this.canvas.width = (this.image.width * ratio);
    	this.canvas.height = (this.image.height * ratio);
    }

	downsizeIfNeeded () {
		let downsizing = false;

		if (this.image.width >= this.proportions.maxWidth) {
			this.canvas.width = this.proportions.maxWidth;
			downsizing = true;
		}

		if (this.image.height >= this.proportions.maxHeight) {
			this.canvas.height = this.proportions.maxHeight;
			downsizing = true;
		}

		if (downsizing) {
			// To combat blur, we're gonna need to add some manipulations.
			this.addManipulations({
				unsharpAmount: 80,
				unsharpRadius: 0.6,
				unsharpThreshold: 2
			});
		}
	}

	upsizeIfNeeded () {
		if (this.image.width <= this.proportions.minWidth) {
			this.canvas.width = this.proportions.minWidth;
		}

		if (this.image.height <= this.proportions.minHeight) {
			this.height.height = this.proportions.minHeight;
		}
	}
}