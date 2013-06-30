/**
 * @author Jeremy
 */

var lastPage = '';
var secondToLastPage = '';

function toggleArrow(linkId)
{
	var arrow = document.getElementById(linkId);
	if (arrow.innerHTML == '+')
	{
		arrow.innerHTML = '--';
	}
	else if (arrow.innerHTML == '--')
	{
		arrow.innerHTML = '+';
	}
}

function toggleElementId(elementId)
{
	toggleElement(elementId, "block");
}

function toggleElement(elementId, display)
{
	var element = document.getElementById(elementId);
	if ((element.style.display != '') && (element.style.display != "none"))
	{
		hideElement(element);
		return false;
	}
	else
	{
		showElement(element, display);
		return true;
	}
	return false;
}

function showElementId(elementId, display)
{
	var element = document.getElementById(elementId);
	showElement(element, display);
}


function showElement(element, display)
{
	if (element != null)
	{
		element.style.display = display;
	}
}

function hideElementId(elementId)
{
	var element = document.getElementById(elementId);
	hideElement(element);
}

function hideElement(element)
{
	if (element != null)
	{
		element.style.display = "none";
	}
}

function toggleBottom(elementId)
{
	var element = document.getElementById(elementId);

	var arrow = document.getElementById(elementId + "Arrow");
	if (toggleElement(elementId + "Bottom", "block"))
	{
		arrow.innerHTML = '<a href="javascript:void(0)">--</a>';
	}
	else
	{
		arrow.innerHTML = '<a href="javascript:void(0)">+</a>';
	}
}

function loading(elementId)
{
	set(elementId, "loading...")
}

function set(elementId, data)
{
	var element = document.getElementById(elementId);
	//element.innerHTML = data;
	document.getElementById("loader").innerHTML = data;
	showElement(element, "block");
}

function loadingData(elementId)
{
	toggleBottom(elementId);
	loading(elementId + "Data");
}

function loadingDiv(elementIdToShow, elementIdToWrite)
{
	hideAllDivSiblings(elementIdToShow);
	showElementId(elementIdToShow, "block");
	loading(elementIdToWrite);
}

function changeToToggle(linkId, bodyId, title)
{
	var link = document.getElementById(linkId);
	link.innerHTML = '<a href="javascript:void(0)" onclick="toggleElementId(\'' + bodyId + '\')">' + title + '</a>';
	var body = document.getElementById(bodyId);
	body.style.display = "block";
}

function convertToShow(linkId, tabId, linkName, linkClass, innerHTML)
{
	var link = document.getElementById(linkId);
	//link.innerHTML = innerHTML;
	var newHTML = getLinkInnerHTML(link);
	var extraChildren = getAjaxChildren(link);
	var a = document.createElement('a');
	a.innerHTML = newHTML;
	a.setAttribute('href', 'javascript:void(0)');
	a.setAttribute('onclick', 'showTab("' + tabId + '", "' + linkClass + '")');
	link.appendChild(a);
	// IE hack .... http://whyiesucks.blogspot.com/2006/07/ie-cannot-set-events-via-dom-in-order.html
	a.parentNode.innerHTML = a.parentNode.innerHTML;
	for (var i = 0; i < extraChildren.length; ++i)
	{
		var child = extraChildren[i];
		link.appendChild(child);
	}
	var page = secondToLastPage;
	secondToLastPage = lastPage;
	lastPage = tabId;
	clearFlashGood();
}

function getAjaxChildren(parent)
{
	var children = parent.childNodes;
	var ajaxChildren = [];
	for (var i = 0; i < children.length; ++i)
	{
		var child = children[i];
		if ((child.nodeName != 'A') && (child.nodeName != 'SCRIPT'))
		{
			ajaxChildren.push(child);
		}
		parent.removeChild(child);
		--i;
	}
	return ajaxChildren;
}

function getExtraTitle(parent)
{
	if (parent.childNodes.length == 0)
	{
		return '';
	}
	var children = parent.childNodes;
	var extra = '';
	for (var i = 0; i < children.length; ++i)
	{
		var child = children[i];
		if ((child.nodeName != 'A') && (child.nodeName != 'SCRIPT'))
		{
			extra += child;
		}
	}
	return extra;
}

function getLinkInnerHTML(parent)
{
	if (parent.childNodes.length == 0)
	{
		return parent.innerHTML;
	}
	var children = parent.childNodes;
	for (var i = 0; i < children.length; ++i)
	{
		var child = children[i];
		if (child.nodeName == 'A')
		{
			return child.innerHTML;
		}
	}
	return null;
}

function showTab(tabId, linkClass)
{
	var page = secondToLastPage;
	secondToLastPage = lastPage;
	lastPage = tabId;
	showElementId(tabId, "block");
	var linkId = tabId + "Action";
	linkClicked(linkId, tabId, linkClass);
	clearFlashGood();
}

function showTabBeforeEdit(defaultTab, msg)
{
//	if (lastPage != '')
//	{
//		alert("showTab:" + lastPage);
//		showTab(lastPage, "currentAction");
//	}
//	else
	{
		showTab(defaultTab, "currentAction");
	}
	setFlashGood(msg);
}

function setFlashGood(msg)
{
	if (msg != '')
	{
		var flashGood = document.getElementById("flashGood");
		flashGood.innerHTML = msg;
		showElement(flashGood, "inline");
	}
}

function clearFlashGood()
{
	var flashGood = document.getElementById("flashGood");
	flashGood.innerHTML = '';
	hideElement(flashGood);
}

function linkClicked(linkId, tabId, linkClass)
{
	hideAllDivSiblings(tabId);
	showRefresh(linkId);
	var link = document.getElementById(linkId);
	if (link && (linkClass != undefined) && (linkClass != 'undefined') && (linkClass != ''))
	{
		link.className = linkClass;
		//link.className = "currentAction";
		var siblings = link.parentNode.childNodes;
		for (var i = 0; i < siblings.length; ++i)
		{
			var child = siblings[i];
			if ((child.nodeType == 1) && (child.getAttribute('id') != linkId))
			{
				child.className = '';
			}
		}
	}
}

function showRefresh(linkId)
{
	var refreshId = linkId + 'Refresh';
	hideAllSiblingsByType(refreshId, 1);
	showElementId(refreshId, "inline");
}

function hideAllSiblingsByType(elementId, type)
{
	var element = document.getElementById(elementId);
	if (element != null)
	{
		var siblings = element.parentNode.childNodes;
		for (var i = 0; i < siblings.length; ++i)
		{
			var child = siblings[i];
			if ((child.nodeType == type) && (child.getAttribute('id') != elementId))
			{
				hideElement(child);
			}
		}
	}
}

function hideAllDivSiblings(elementId, linkNotToHideId)
{
	hideAllSiblingsByType(elementId, 1);
}

function completeData(elementId)
{
	var arrow = document.getElementById(elementId + "Arrow");
	arrow.innerHTML = '<a title="Hide Info" href="javascript:void(0)">--</a>';
	//arrow.setAttribute('onclick', 'toggleBottom("' + elementId + '")');
	// setAttribute not working in IE 7 -- I wonder if new Function works in IE 6
	arrow.onclick = new Function('toggleBottom("' + elementId + '"); toggleElement("' + elementId + 'Refresh", "inline");');
	
	showElementId(elementId + "Refresh", "inline");
}

function refreshing(elementId)
{
	set(elementId, "refreshing...");
}
