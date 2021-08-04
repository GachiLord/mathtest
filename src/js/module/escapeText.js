export default function escape_text(text) { 
	let map = {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'};
	return text.replace(/[&<>"']/g, function(m) {
		return map[m];
	});
}