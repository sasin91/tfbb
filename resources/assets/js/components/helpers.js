import Photo from './photo';

/**
 * Prepare a photo for uploading to the API.
 * 
 * @param  File file 
 * @return Promise
 */
export function photo (file) {
	return new Photo(file).prepareForUpload();
}

/**
 * Fill FormData with files from given inputEvent
 * 
 * @param  Event 			inputEvent 
 * @param  String 			key        
 * @param  FormData | null	formData   
 * @return FormData            
 */
export function formData(inputEvent, key = 'file', formData = null) {
	const data = formData ? formData : new FormData;

	for (var i = 0; i < inputEvent.target.files.length; i++) {
		const file = inputEvent.target.files[i];

		data.append(key, file);
	}

	return data;
}

/**
 * Fill FormData with files from given inputEvent
 * 
 * @param  Event 			inputEvent 
 * @param  String 			key        
 * @param  FormData | null	formData   
 * @return FormData            
 */
export function formDataArray(inputEvent, key, formData = null) {
	const data = formData ? formData : new FormData;

	for (var i = 0; i < inputEvent.target.files.length; i++) {
		const file = inputEvent.target.files[i];

		data.append(`${key}[${i}]`, file);
	}

	return data;
}