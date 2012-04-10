	checkForm = function (event) {
		elems = Form.getElements(Event.element(event));
		
		var flag = true;
		
		Form.getElements(Event.element(event)).each(function (element) {
			if (element.getAttribute('checkable') == 1) {
				
				if (element.check) {
					res = element.check(element);
				}
				else if (check_value = element.getAttribute('check_value')) {
					res = ($F(element).match (new RegExp(check_value)) != null)
				}
				else {
					res = ($F(element).length > 0)
				}
				
				if (!res) {
					element.className = "error " + element.className;
				}
				else {
					element.className = element.className.gsub("error", '')
				}

				flag = flag && res
			}
		});
				
		return flag;
	}
	
	window.onload = function (event) {
		$A(document.getElementsByTagName("FORM")).each(function (node) {
			if (node.getAttribute('checkable') == 1)
				node.onsubmit = checkForm.bindAsEventListener();
		});
	}.bindAsEventListener();