import { map, forEach } from 'lodash';

class FileUploadQueue {
	/**
	 * Create the upload queue
	 * 
	 * @param  Array | Object supported [{name:'photos', mime:'image'}]
	 * @return void
	 */
	constructor (supported = []) {
		this.config = {};
		this.queue = {};

		forEach(supported, (config) => {
			this.config[config.name] = config;
			this.queue[config.name] = [];
		});
	}

	/**
	 * Get the queues handling given file
	 * 
	 * @param  File file 
	 * @return Object      
	 */
	handling (file) {
		return map(this.config, (config) => {
			const isHandling = new RegExp(config.mime, 'g').test(file.type);

			if (isHandling) {
				return {
					config: config,
					queue: this.queue[config.name]
				};
			}

			return {
				config: {},
				queue: []
			};
		});
	}

	/**
	 * The default do-nothing transformer
	 * 
	 * @param File file 
	 * @return File
	 */
	defaultTransformer (file) {
		return file;
	}

	/**
	 * Wrap given transformer in a promise if it is not already one.
	 * 
	 * @param Function | Promise transformer
	 * @param File file
	 * @return Promise
	 */
	transformerPromise (transformer, file) {
		if (transformer instanceof Promise) {
			return transformer;
		}

		return new Promise((resolve) => resolve(transformer(file)));
	}

	/**
	 * Remove a file from the queues
	 * 
	 * @param  File file 
	 * @return null      
	 */
	remove (file) {
		let queues = this.handling(file);

		queues = queues.filter(queuedFile => queuedFile !== file);
	}

	/**
	 * Add a file a queue
	 * 	
	 * @param File file 
	 */
	add (file) {
		if (file instanceof File) {
			this.handling(file).forEach((handler) => {
				const transformer = handler.config.transformer ? handler.config.transformer : this.defaultTransformer;

				this.transformerPromise(transformer, file).then((result) => handler.queue.push(result));
			});
		} else {
			this.addMany(file);
		}
	}

	/**
	 * Add many files to the queue(s)
	 * 
	 * @param Array files 
	 */
	addMany(files) {
		for (var i = 0; i < files.length; i++) {
			this.add(files[i]);
		}
	}

	/**
	 * Reset the queues
	 * 
	 * @return void
	 */
	reset () {
		forEach(this.config, (config) => {
			this.queue[config.name] = [];
		});
	}

	/**
	 * Upload the files in queue using given callback
	 * 
	 * @param  Function callback 	
	 * @return Promise[]
	 */
	upload (callback = null) {
		return this.process((files, name) => {
			const formData = new FormData;

			for (var i = 0; i < files.length; i++) {
				formData.append(`${name}[${i}]`, files[i]);
			}

			return this._executeHandler(callback, formData, name);
		});
	}

	/**
	 * Process the queues
	 * 
	 * @param  Function callback 
	 * @return Promise[]          
	 */
	process (callback = null) {
		return map(this.queue, (files, name) => {
			const handler = callback ? callback : this.config[name].handler;

			return this._executeHandler(handler, files, name);
		});
	}

	async _executeHandler (handler, files, name) {
		try {
			const response = await handler(files, name);

			files = [];

			return response;
		} catch (errors) {
			throw errors;
		}
	}
}

export default FileUploadQueue