// @author Rob W <http://stackoverflow.com/users/938089/rob-w>
// Demo: var serialized_html = DOMtoString(document);

function DOMtoString(document_root) {

	var html = '',
	node = document_root.firstChild;
	while (node) {
		switch (node.nodeType) {
		case Node.ELEMENT_NODE:
			html += "==========ELEMENT_NODE\n" +
			node.outerHTML;

			//--------------------------

			var metas = document_root.getElementsByTagName('meta');
			var trace = ">>>>>>>>>>";

			for (var i = 0; i < metas.length; i++) {
				if (metas[i].getAttribute("property") == "keywords") {
					//return metas[i].getAttribute("content");
					trace += metas[i].getAttribute(" content ");
					break;
				}
			}
			console.log(trace);
			//--------------------------


			break;
		case Node.TEXT_NODE:
			html += " ===  ===  ===  = TEXT_NODE \ n " +
			node.nodeValue;
			break;
		case Node.CDATA_SECTION_NODE:
			html += " ===  ===  ===  = CDATA_SECTION_NODE \ n " +
			'<![CDATA[' + node.nodeValue + ']]>';
			break;
		case Node.COMMENT_NODE:
			html += " ===  ===  ===  = COMMENT_NODE \ n " +
			'<!--' + node.nodeValue + '-->';
			break;
		case Node.DOCUMENT_TYPE_NODE:
			// (X)HTML documents are identified by public identifiers
			html += " ===  ===  ===  = DOCUMENT_TYPE_NODE \ n " +
			" < !DOCTYPE " + node.name + (node.publicId ? ' PUBLIC " ' + node.publicId + ' "' : '') + (!node.publicId && node.systemId ? ' SYSTEM' : '') + (node.systemId ? ' " ' + node.systemId + ' "' : '') + '>\n';
			break;
		}
		node = node.nextSibling;
	}

	//---------------------------------------------------------------------------------------------
	//alert(" ===  ===  ===  = DOMtoString \ n " + html); // OK !!! works
	//console.log(html);
	//---------------------------------------------------------------------------------------------
	var content01 = " ";
	/*
	var elt = document_root.getElementById('keywords');

	content01 = elt.innerHTML + " ##  # ";
	 */
	/*
	var metaArray = document_root.getElementsByName('keywords');
	for (var i = 0; i < metaArray.length; i++) {
	//document.write(metaArray[i].content + '<br>');
	content01 += metaArray[i].content + " # " + metaArray[i].innerHTML + " \ n ";
	}
	 */

	//alert(" ===  ===  ===  = DOMtoString \ n " + content01); // OK !!! works

	//---------------------------------------------------------------------------------------------

	return html;
}

chrome.runtime.sendMessage({
	action : " getSource ",
	source : DOMtoString(document)
});
