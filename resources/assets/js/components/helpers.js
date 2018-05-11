import Photo from './photo';
import { deburr } from 'lodash';

/**
 * Create a File Blob instance from given url
 * 
 * @param  String url 
 * @return File
 */
export async function fileFromUrl (url) {
	const response = await fetch(url);	

	return response.blob();
}

/**
 * Create an array of file blobs from given urls
 * 
 * @param  Array urls
 * @return Blob[]     
 */
export async function filesFromUrls (urls) {
	const filePromises = urls.map(url => fetch(url));
	const fileBlobs = await Promise.all(filePromises).then(files => files.map(async file => await file.blob()));

	return Promise.all(fileBlobs);
}

/**
 * Get a cookie value by name
 * 
 * @param  string cname 
 * @return string      
 */
export function cookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

/**
 * Prepare a photo for uploading to the API.
 * 
 * @param  File file 
 * @return File
 */
export async function photo (file) {
	const { photo } = await new Photo(file).prepareForUpload();

	return photo;
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

/**
 * Generates a url-safe "slug" form of a string.
 *
 * @credit https://github.com/helion3/lodash-addons/blob/master/dist/lodash-addons.js#L617
 * @category String
 * @param {string} string String value.
 * @return {string} URL-safe form of a string.
 * @example
 *
 * _.slugify('A Test');
 * // => a-test
 */
export function slugify(string) {
  return deburr(string).trim().toLowerCase().replace(/ /g, '-').replace(/([^a-zA-Z0-9\._-]+)/, '');
}