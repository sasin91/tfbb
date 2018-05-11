module.exports = {
	data () {
		return {
			editorOptions: { 
				modules: { 
					markdownShortcuts:{},
					toolbar: [
						[{
							header: [1,2,3,4,5]
						}],
						[
							'blockquote', 'code-block', 
							'bold', 'italic', 'underline', 'strike',
						],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],	
					]
				} 
			},
		}
	}
}