/*
Copyright (c) 2008-2011, www.redips.net All rights reserved.
Code licensed under the BSD License: http://www.redips.net/license/
http://www.redips.net/javascript/drag-and-drop-table-content/
Version 5.0.5
Dec 27, 2012.
*/

/*jslint white: true, browser: true, undef: true, nomen: true, eqeqeq: true, plusplus: false, bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxerr: 14 */
/*global window: false */

/* reveal module-pattern */

/* enable strict mode */
"use strict";


/**
 * @name REDIPS
 * @description create REDIPS namespace (if is not already defined in another REDIPS package)
 */
var REDIPS = REDIPS || {};


/**
 * @namespace
 * @description REDIPS.drag is a JavaScript drag and drop library focused on dragging table content (DIV elements) and table rows.
 * @name REDIPS.drag
 * @author darko.bunic@google.com (Darko Bunic)
 * @see
 * <a href="http://www.redips.net/javascript/redips-drag-documentation-appendix-a/" title="REDIPS.drag documentation - Appendix A">REDIPS.drag documentation - Appendix A</a>
 *  
 * <a href="http://www.redips.net/javascript/drag-and-drop-table-content-animation/">Drag and drop table content plus animation</a>
 * <a href="http://www.redips.net/javascript/drag-and-drop-table-row/">Drag and drop table rows</a>
 * <a href="http://www.redips.net/javascript/drag-and-drop-table-content/">Drag and Drop table content</a>
 * <a href="http://www.redips.net/javascript/drag-and-drop-content-shift/">JavaScript drag and drop plus content shift</a>
 * @version 5.0.5 (2012-12-27)
 */
REDIPS.drag = (function () {
		// methods
	var	init,						// initialization
		initTables,					// table initialization
		enableDrag,					// method attaches / detaches onmousedown and onscroll event handlers for DIV elements
		enableTable,				// method enables / disables tables (selected by className) to accept elements 
		imgOnMouseMove,				// needed to set onmousemove event handler for images
		handlerOnMouseDown,			// onmousedown handler
		handlerOnDblClick,			// ondblclick handler (calls public event.dblClicked)
		tableTop,					// set current table group in "tables" array to the array top
		handlerOnMouseUp,			// onmouseup handler
		handlerOnMouseMove,			// onmousemove handler for the document level
		elementDrop,				// drop element to the table cell
		elementDeleted,				// actions needed after element is deleted (call event handler, updatig, climit1_X or climit2_X classnames, content shifting ...)
		resetStyles,				// reset object styles after element is dropped
		registerEvents,				// register event listeners for DIV element
		cellChanged,				// private method called from handlerOnMouseMove(), autoScrollX(), autoScrollY()
		handlerOnResize,			// onresize window event handler
		setTableRowColumn,			// function sets current table, row and cell
		setPosition,				// function sets color for the current table cell and remembers previous position and color
		setTdStyle,					// method sets background color and border styles for TD
		getTdStyle,					// method returns object containing background color and border styles for TD
		boxOffset,					// calculates object (box) offset (top, right, bottom, left)
		calculateCells,				// calculates table columns and row offsets (cells dimensions)
		getScrollPosition,			// returns scroll positions in array
		autoScrollX,				// horizontal auto scroll function
		autoScrollY,				// vertical auto scroll function
		cloneObject,				// clone object (DIV element)
		copyProperties,				// method copies custom properties from source element to the cloned element.
		cloneLimit,					// clone limit (after cloning object, take care about climit1_X or climit2_X classnames)
		elementControl,				// method returns true or false if element needs to have control
		getStyle,					// method returns style value of requested object and style name
		findParent,					// method returns a reference of the required parent element
		findCell,					// method returns first or last cell: rowIndex, cellIndex and cell reference (input is "first" or "last" parameter and table or object within table)
		saveContent,				// scan tables, prepare query string and sent to the multiple-parameters.php
		relocate,					// relocate objects from source cell to the target cell (source and target cells are input parameters)
		emptyCell,					// method removes elements from table cell
		shiftCells,					// method shifts table content to the left or right (useful for content where the order should be preserved)
		cellList,					// method returns cell list with new coordinates (it takes care about rowspan/colspan cells) 
		maxCols,					// method returns maximum number of columns in a table
		moveObject,					// method moves object to the destination table, row and cell
		deleteObject,				// method deletes DIV element
		animateObject,				// object animation
		getTableIndex,				// find table index - because tables array is sorted on every element click
		getPosition,				// returns position in format: tableIndex, rowIndex and cellIndex (input parameter is optional)
		rowOpacity,					// method sets opacity to table row (el, opacity, color)
		rowEmpty,					// method marks selected row as empty (input parameters are table index and row index)
		rowClone,					// clone table row - input parameter is DIV with class name "row" -> DIV class="drag row"
		rowDrop,					// function drops (delete old & insert new) table row (input parameters are current table and row)
		formElements,				// set form values in cloned row (to prevent reset values of form elements)
		normalize,					// private method returns normalized spaces from input string
		hasChilds,					// private method (returns true if element contains child nodes with nodeType === 1)
	
		// private parameters
		objMargin = null,			// space from clicked point to the object bounds (top, right, bottom, left)
		
		// window width and height (parameters are set in onload and onresize event handler)
		// just for a note: window and Window is reserved word in JS so I named variable "screen")
		screen = {width: 0,
				height: 0},

		// define scroll object with contained properties (this is private property)
		scrollData = {width : null,	// scroll width of the window (it is usually greater then window)
				height : null,		// scroll height of the window (it is usually greater then window)
				container : [],		// scrollable container areas (contains autoscroll areas, reference to the container and scroll direction)
				obj : null},		// scroll object (needed in autoscroll for recursive calls)
		
		edge = {page: {x: 0, y: 0}, // autoscroll bound values for page and div as scrollable container
				div:  {x: 0, y: 0},	// closer to the edge, faster scrolling
				flag: {x: 0, y: 0}},// flags are needed to prevent multiple calls of autoScrollX and autoScrollY from onmousemove event handler

		bgStyleOld,				// (object) old td styles (background color and border styles)

		tables = [],				// table offsets and row offsets (initialized in onload event)
		sortIdx,					// sort index needed for sorting tables in tableTop()
		moved,						// (boolean) true if element is moved
		cloned,						// (boolean) true if element is cloned
		clonedId = [],				// needed for increment ID of cloned elements
		currentCell = [],			// current cell bounds (top, right, bottom, left) and "containTable" flag for nested tables
		dragContainer = null,		// drag container reference
		divBox = null,				// div drag box: top, right, bottom and left margin (decrease number calls of setTableRowColumn)
		pointer = {x: 0, y: 0},		// mouse pointer position (this properties are set in handlerOnMouseMove() - needed for autoscroll)
		threshold = {x: 0,			// initial x, y position of mouse pointer
					y: 0,
					value: 7,		// threshold distance value
					flag: false},	// threshold flag
		shiftKey = false,			// (boolean) true if shift key is pressed (set in handler_mousedown)
		cloneClass = false,		// (boolean) true if clicked element contains clone in class name (set in handler_mousedown)
		animationCounter = [],		// (array) counter of animated elements to be shifted before table should be enabled
		
		// selected, previous and source table, row and cell (private parameters too)
		table = null,
		table_old = null,
		table_source = null,
		row = null,
		row_old = null,
		row_source = null,
		cell = null,
		cell_old = null,
		cell_source = null,
		
		// variables in the private scope revealed as public (see init() method)
		obj = false,				// (object) moved object
		objOld = false,				// (object) previously moved object (before clicked or cloned)
		mode = 'cell',				// (string) drag mode: "cell" or "row" (default is cell)
		// (object) defines color and border styles for current TD and TR
		// hover.borderTr defines border color used in "row" mode to show whether row will be dropped above or below current row
		// borderTd and borderTr are initially undefined
		hover = {colorTd: '#E7AB83',
				colorTr: '#E7AB83'},
		scroll = {enable : true,	// (boolean) enable/disable autoscroll function (default is true) 
				bound : 25,			// (integer) bound width for autoscroll
				speed : 20},		// (integer) scroll speed in milliseconds
		only = {div: [],			// (array) DIVid -> classname, defined DIV elements can be placed only to the marked table cell with class name 'only'
				cname: 'only',		// (string) class name for marked cells (default is 'only') - only defined objects can be placed there
				other: 'deny'},		// (string) allow / deny dropping marked objects with "only" to other cells
		mark = {action: 'deny',
				cname: 'mark',
				exception: []},
		style = {borderEnabled : 'solid',	// (string) border style for enabled elements
				borderDisabled : 'dotted',	// (string) border style for disabled elements
				opacityDisabled : '',		// (integer) set opacity for disabled elements
				rowEmptyColor : 'white'},	// (string) color of empty row
		tableSort = true,				// (boolean) sort tables on DIV element click
		trash = {className : 'trash',	// (object) contains trash class name and confirmation questions for delete DIV or ROW	
				question : null,
				questionRow : null},
		saveParamName = 'p',			// (string) save content parameter name
		dropMode = 'multiple',			// (string) dropMode has the following options: multiple, single, switch, switching and overwrite
		multipleDrop = 'bottom',		// (string) defines position of dropped element in case of 'multiple' drop option
		td = {},						// (object) contains reference to source (set in onmousedown), current (set in onmousemove and autoscroll), previous (set in onmousemove and autoscroll) and target cell (set in onmouseup)
		animation = {pause : 20,		// (object) animation pause (integer), step (integer) and shift (boolean)
					step: 2,
					shift: false},
		// (object)
		shift = {mode : 'horizontal1',	// shift modes (horizontal1, horizontal2, vertical1, vertical2) 
				after : 'default',		// how to shift elements (always, if DIV element is dropped to the empty cell as well or if DIV element is deleted)
				overflow : 'bunch'},	// what to do with overflowed DIV (bunch, delete, source)
		clone = {keyDiv : false,		// (boolean) if true, elements could be cloned with pressed SHIFT key
				keyRow : false,			// (boolean) if true, rows could be cloned with pressed SHIFT key
				sendBack : false,		// (boolean) if true, then cloned element can be returned to its source
				drop : false},			// (boolean) if true, then cloned element will be always dropped to the table no matter if dropped outside of the table
		rowDropMode = 'before',			// (string) drop row before or after highlighted row (values are "before" or "after")
		// (object) event handlers
		event = {changed : function () {},
				clicked : function () {},
				cloned : function () {},
				clonedDropped : function () {},
				clonedEnd1 : function () {},
				clonedEnd2 : function () {},
				dblClicked : function () {},
				deleted : function () {},
				dropped : function () {},
				droppedBefore : function () {},
				finish : function () {},
				moved : function () {},
				notCloned : function () {},
				notMoved : function () {},
				shiftOverflow: function () {},
				relocateBefore : function () {},
				relocateAfter : function () {},
				relocateEnd : function () {},
				rowChanged : function () {},
				rowClicked : function () {},
				rowCloned : function () {},
				rowDeleted : function () {},
				rowDropped : function () {},
				rowDroppedBefore : function () {},
				rowDroppedSource : function () {},
				rowMoved : function () {},
				rowNotCloned : function () {},
				rowNotMoved : function () {},
				rowUndeleted : function () {},
				switched : function () {},
				undeleted : function () {}};


	/**
	 * Drag container initialization. It should be called at least once and it's possible to call a method many times.
	 * Every page should have at least one drag container.
	 * If REDIPS.drag.init() is called without input parameter, library will search for drag container with id="drag".
	 * Only tables inside drag container will be scanned. It is possible to have several drag containers totaly separated (elements from one container will not be visible to other drag containers).
	 * "init" method calls initTables and enableDrag.
	 * @param {String} [dc] Drag container Id (default is "drag").
	 * @example
	 * // init drag container (with default id="drag")
	 * REDIPS.drag.init();
	 *  
	 * // init drag container with id="drag1"
	 * REDIPS.drag.init('drag1');
	 * @public
	 * @see <a href="#initTables">initTables</a>
	 * @see <a href="#enableDrag">enableDrag</a>
	 * @function
	 * @name REDIPS.drag#init
	 */
	init = function (dc) {
		// define local variables
		var self = this,		// assign reference to current object to "self"
			i,					// used in local for loops
			imgs,				// collect images inside div=drag
			redipsClone;		// reference to the DIV element needed for cloned elements 
		// if drag container is undefined or input parameter is not a string, then set reference to DIV element with default id="drag"
		if (dc === undefined || typeof(dc) !== 'string') {
			dc = 'drag';
		}
		// set reference to the drag container
		dragContainer = document.getElementById(dc);
		// append DIV id="redips_clone" if DIV doesn't exist (needed for cloning DIV elements)
		// if automatic creation isn't precise enough, user can manually create and place element with id="redips_clone" to prevent window expanding
		// (then this code will be skipped)
		if (!document.getElementById('redips_clone')) {
			redipsClone = document.createElement('div');
			redipsClone.id = 'redips_clone';
			redipsClone.style.width = redipsClone.style.height = '1px';
			dragContainer.appendChild(redipsClone);
		}
		// attach onmousedown event handler to the DIV elements
		// attach onscroll='calculateCells' for DIV elements with 'scroll' in class name (prepare scrollable container areas)
		enableDrag('init');
		// initialize table array
		// here was the following comment: "initTables should go after enableDrag because sca is attached to the table if table belongs to the scrollable container"
		// not sure about order of enableDrag and initTable - needed some further testing
		initTables();
		// set initial window width/height, scroll width/height and define onresize event handler
		// onresize event handler calls calculate columns
		handlerOnResize();
		REDIPS.event.add(window, 'resize', handlerOnResize);
		// collect images inside drag container
		imgs = dragContainer.getElementsByTagName('img');
		// disable onmousemove/ontouchmove event for images to prevent default action of onmousemove event (needed for IE to enable dragging on image)
		for (i = 0; i < imgs.length; i++) {
			REDIPS.event.add(imgs[i], 'mousemove', imgOnMouseMove);
			REDIPS.event.add(imgs[i], 'touchmove', imgOnMouseMove);
		}
		// attach onscroll event to the window (needed for recalculating table cells positions)
		REDIPS.event.add(window, 'scroll', calculateCells);
	};


	/**
	 * Needed to set "false" for onmousemove event on images. This way, images from DIV element will not be enabled for dragging by default.
	 * imgOnMouseMove is attached as handler to all images inside drag container.
	 * Multiple calling of REDIPS.drag.init() will not attach the same event handler to the images.
	 * @private
	 * @memberOf REDIPS.drag#
	 * @see <a href="#init">init</a>
	 */
	imgOnMouseMove = function () {
		return false;
	};


	/**
	 * Tables layout initialization (preparing internal "tables" array).
	 * Method searches for all tables inside defined selectors and prepares "tables" array. Defaule selector is "#drag table".
	 * Tables with className "nolayout" are ignored (e.g. table with "nolayout" class name in DIV element can be dragged as any other content). 
	 * "tables" array is one of the main parts of REDIPS.drag library.
	 * @example
	 * // call initTables after new empty table is added to the div#drag
	 * // REDIPS.init() method will also work but with some overhead 
	 * REDIPS.drag.initTables();
	 *  
	 * // change default selector for table search (div#sticky may contain table that is attached out of defaule drag container)
	 * // this means that table should not be a part of div#drag, but it should be positioned within drag container otherwise dragging will not work
	 * REDIPS.drag.initTables('#drag table, #sticky table');
	 *  
	 * // if new table is not empty and contains DIV elements then they should be enabled also
	 * // DIV elements in "drag" container are enabled by default
	 * REDIPS.drag.initTables('#drag table, #sticky table');
	 * REDIPS.drag.enableDrag(true, '#sticky div');
	 * @param {String} [selector] Defines selector for table search (default id "#drag table").
	 * @public
	 * @function
	 * @name REDIPS.drag#initTables
	 */
	initTables = function (selector) {
		var	i, j, k,			// loop variables
			tblSelector,		// table selectors
			element,			// used in searhing parent nodes of found tables below div id="drag"
			level,				// (integer) 0 - ground table, 1 - nested table, 2 - nested nested table, 3 - nested nested nested table ...
			groupIdx,			// tables group index (ground table and its nested tables will have the same group)
			tableNodeList,		// nodelist of tables found inside defined selectors (querySelector returns node list and it's not alive)
			nestedTables,		// nested tables nodelist (search for nested tables for every "ground" table)
			tdNodeList,			// td nodeList (needed for search rowspan attribute)
			rowspan;			// flag to set if table contains rowspaned cells
		// empty tables array
		// http://stackoverflow.com/questions/1232040/how-to-empty-an-array-in-javascript
		tables.length = 0;
		// if selector is undefined then use reference of current drag container
		if (selector === undefined) {
			tableNodeList = dragContainer.getElementsByTagName('table');
		}
		// otherwise prepare tables node list based on defined selector
		// node list returned by querySelectorAll is not alive
		else {
			tableNodeList = document.querySelectorAll(selector);
		}
		// loop through tables and define table sort parameter
		for (i = 0, j = 0; i < tableNodeList.length; i++) {
			// skip table if table belongs to the "redipsClone" container (possible for cloned rows - if initTables() is called after rowClone())
			// or table has "nolayout" className
			if (tableNodeList[i].parentNode.id === 'redips_clone' || tableNodeList[i].className.indexOf('nolayout') > -1) {
				continue;
			}
			// set start element for "do" loop
			element = tableNodeList[i].parentNode;
			// set initial value for nested level
			level = 0;
			// go up through DOM until DIV id="drag" found (drag container)
			do {
				// if "TD" found then this is nested table
				if (element.nodeName === 'TD') {
					// increase nested level
					level++;
				}
				// go one level up
				element = element.parentNode;
			} while (element && element !== dragContainer);
			// copy table reference to the static list
			tables[j] = tableNodeList[i];
			// create a "property object" in which all custom properties will be saved (if "redips" property doesn't exist)
			if (!tables[j].redips) {
				tables[j].redips = {};
			}
			// set redips.container to the table (needed in case when row is cloned)
			// ATTENTION! Here is needed additional work because table outside div#drag can be added to the "tables" array ...
			tables[j].redips.container = dragContainer;
			// set nested level (needed for sorting in "tables" array)
			// level === 0 - means that this is "ground" table ("ground" table may contain nested tables)
			tables[j].redips.nestedLevel = level;
			// set original table index (needed for sorting "tables" array to the original order in saveContent() function)
			tables[j].redips.idx = j;
			// define animationCounter per table
			animationCounter[j] = 0;
			// prepare td nodeList of current table
			tdNodeList = tables[j].getElementsByTagName('td');
			// loop through nodeList and search for rowspaned cells
			for (k = 0, rowspan = false; k < tdNodeList.length; k++) {
				// if only one rowspaned cell is found set flag to "true" and break loop
				if (tdNodeList[k].rowSpan > 1) {
					rowspan = true;
					break;
				}
			}
			// set redips.rowspan flag - needed in setTableRowColumn()
			tables[j].redips.rowspan = rowspan;
			// increment j counter
			j++;
		}
		/*
		 * define "redips.nestedGroup" and initial "redips.sort" parameter for each table
		 * 
		 * for example if drag area contains two tables and one of them has nested tables then this code will create two groups
		 * with the following redips.sort values: 100, 200, and 201
		 * 100 - "ground" table of the first group
		 * 200 - "ground" table of the second group
		 * 201 - nested table of the second table group
		 * 
		 * this means that nested table of second group will always be sorted before its "ground" table
		 * after clicking on DIV element in "ground" table of second group or nested table in second group array order will be: 201, 200 and 100
		 * after clicking on DIV element in "ground" table of first group array order will be: 100, 201, 200
		 * 
		 * actually, sortIdx will be increased and sorted result will be: 300, 201, 200
		 * and again clicking on element in nested table sorted result will be: 401, 400, 300 
		 * and so on ...
		 */
		for (i = 0, groupIdx = sortIdx = 1; i < tables.length; i++) {
			// if table is "ground" table (lowest level) search for nested tables
			if (tables[i].redips.nestedLevel === 0) {
				// set group index for ground table and initial sort index
				tables[i].redips.nestedGroup = groupIdx;
				tables[i].redips.sort = sortIdx * 100;
				// search for nested tables (if there is any)
				nestedTables = tables[i].getElementsByTagName('table');
				// open loop for every nested table
				for (j = 0; j < nestedTables.length; j++) {
					// skip table if table contains "nolayout" className
					if (nestedTables[j].className.indexOf('nolayout') > -1) {
						continue;
					}
					// set group index and initial sort index
					nestedTables[j].redips.nestedGroup = groupIdx;
					nestedTables[j].redips.sort = sortIdx * 100 + nestedTables[j].redips.nestedLevel;
				}
				// increase group index and sort index (sortIdx is private parameter of REDIPS.drag)
				groupIdx++;
				sortIdx++;
			}
		}
	};



	/**
	 * onmousedown event handler.
	 * This event handler is attached to every DIV element in drag container (please see "enableDrag").
	 * @param {Event} e Event information.
	 * @see <a href="#enableDrag">enableDrag</a>
	 * @see <a href="#add">handlerOnDblClick</a>
	 * @see <a href="#add_events">add_events</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	handlerOnMouseDown = function (e) {
		var evt = e || window.event,	// define event (cross browser)
			offset,						// object offset
			mouseButton,				// start drag if left mouse button is pressed
			position,					// position of table or container box of table (if has position:fixed then exclude scroll offset)
			X, Y;						// X and Y position of mouse pointer
		// if current DIV element is animated, then disable dragging of this element
		if (this.redips.animated === true) {
			return true;
		}
		// stop event propagation (only first clicked element will register onmousedown event)
		// needed in case of placing table inside of <div class="drag"> (after element was dropped to this table it couldn't be moved out
		// any more - table and element moved together because table captures mousedown event also in bubbling proces)
		evt.cancelBubble = true;
		if (evt.stopPropagation) {
			evt.stopPropagation();
		}
		// set true or false if shift key is pressed
		shiftKey = evt.shiftKey;
		// define which mouse button was pressed
		if (evt.which) {
			mouseButton = evt.which;
		}
		else {
			mouseButton = evt.button;
		}
		// exit from event handler if:
		// 1) control should pass to form elements and links
		// 2) device is not touch device and left mouse button is not pressed
		if (elementControl(evt) || (!evt.touches && mouseButton !== 1)) {
			return true;
		}
		// remove text selection (Chrome, FF, Opera, Safari)
		if (window.getSelection) {
			window.getSelection().removeAllRanges();
		}
		// IE8
		else if (document.selection && document.selection.type === "Text") {
			try {
				document.selection.empty();
			}
			catch (error) {
				// ignore error to as a workaround for bug in IE8
			}
		}
		// define X and Y position (pointer.x and pointer.y are needed in setTableRowColumn() and autoscroll methods) for touchscreen devices
		if (evt.touches) {
			X = pointer.x = evt.touches[0].clientX;
			Y = pointer.y = evt.touches[0].clientY;
		}
		// or for monitor + mouse devices
		else {
			X = pointer.x = evt.clientX;
			Y = pointer.y = evt.clientY;
		}
		// set initial threshold position (needed for calculating distance)
		threshold.x = X;
		threshold.y = Y;
		threshold.flag = false;
		// remember previous object if defined or set to the clicked object
		REDIPS.drag.objOld = objOld = obj || this;
		// set reference to the clicked object
		REDIPS.drag.obj = obj = this;
		// set true or false if clicked element contains "clone" class name (needed for clone element and clone table row)
		cloneClass = obj.className.indexOf('clone') > -1 ? true : false;
		// if tableSort is set to true (this is default) then set current table group in "tables" array to the array top
		// tableTop() should go before definition of "mode" property
		if (REDIPS.drag.tableSort) {
			tableTop(obj);
		}
		// if clicked element doesn't belong to the current container then environment should be changed
		if (dragContainer !== obj.redips.container) {
			dragContainer = obj.redips.container;
			initTables();
		}
		// define drag mode ("cell" or "row")
		// mode definition should be after:
		// tableTop() - because "obj" is rewritten with table row reference
		// initTables() - because "obj" is rewritten with table row reference and row doesn't have defined redips.container property
		if (obj.className.indexOf('row') === -1) {
			REDIPS.drag.mode = mode = 'cell';
		}
		else {
			REDIPS.drag.mode = mode = 'row';
			// just return reference of the current row (do not clone)
			REDIPS.drag.obj = obj = rowClone(obj);
		}
		// if user has used a mouse event to increase the dimensions of the table - call calculateCells() 
		calculateCells();
		// set high z-index if object isn't "clone" type (clone object is motionless) for "cell" mode only
		if (!cloneClass && mode === 'cell') {
			// http://foohack.com/2007/10/top-5-css-mistakes/ (how z-index works)
			obj.style.zIndex = 999;
		}
		// reset table row and cell indexes (needed in case of enable / disable tables)
		table = row = cell = null;
		// set current table, row and cell and remember source position (old position is initially the same as source position) 
		setTableRowColumn();
		table_source = table_old = table;
		row_source = row_old = row;
		cell_source = cell_old = cell;
		// define source cell, current cell and previous cell (needed for event handlers)
		REDIPS.drag.td.source = td.source = findParent('TD', obj);
		REDIPS.drag.td.current = td.current = td.source;
		REDIPS.drag.td.previous = td.previous = td.source;
		// call event.clicked for table content
		if (mode === 'cell') {
			REDIPS.drag.event.clicked(td.current);
		}
		// or for table row
		else {
			REDIPS.drag.event.rowClicked(td.current);
		}
		// if start position cannot be defined then user probably clicked on element that belongs to the disabled table
		// (or something else happened that was not supposed to happen - every element should belong to the table)
		// this code must go after execution of event handlers
		if (table === null || row === null || cell === null) {
			// rerun setTableRowColumn() again because some of tables might be enabled in handler events above
			setTableRowColumn();
			table_source = table_old = table;
			row_source = row_old = row;
			cell_source = cell_old = cell;
			// no, clicked element is on the disabled table - sorry
			if (table === null || row === null || cell === null) { 
				return true;
			}
		}
		// reset "moved" flag (needed for clone object in handlerOnMouseMove) and "cloned" flag
		moved = cloned = false;
		// activate onmousemove and ontouchmove event handlers on document object
		REDIPS.event.add(document, 'mousemove', handlerOnMouseMove);
		REDIPS.event.add(document, 'touchmove', handlerOnMouseMove);
		// activate onmouseup and ontouchend event handlers on document object
		REDIPS.event.add(document, 'mouseup', handlerOnMouseUp);
		REDIPS.event.add(document, 'touchend', handlerOnMouseUp);
		// get IE (all versions) to allow dragging outside the window (?!)
		// http://stackoverflow.com/questions/1685326/responding-to-the-onmousemove-event-outside-of-the-browser-window-in-ie
		if (obj.setCapture) {
			obj.setCapture();
		}
		// remember background color if is possible
		if (table !== null && row !== null && cell !== null) {
			bgStyleOld = getTdStyle(table, row, cell);
		}
		// set table CSS position (needed for exclusion "scroll offset" if table box has position fixed)
		position = getStyle(tables[table_source], 'position');
		// if table doesn't have style position:fixed then table container should be tested 
		if (position !== 'fixed') {
			position = getStyle(tables[table_source].parentNode, 'position');
		}
		// define object offset
		offset = boxOffset(obj, position);
		// calculate offset from the clicked point inside element to the
		// top, right, bottom and left side of the element
		objMargin = [Y - offset[0], offset[1] - X, offset[2] - Y, X - offset[3]];
		// dissable text selection (but not for links and form elements)
		// onselectstart is supported by IE browsers, other browsers "understand" return false in onmousedown handler
		dragContainer.onselectstart = function (e) {
			evt = e || window.event;
			if (!elementControl(evt)) {
				// this lines are needed for IE8 in case when leftmouse button was clicked and SHIFT key was pressed
				// IE8 selected text anyway but document.selection.clear() prevented text selection
				if (evt.shiftKey) {
					document.selection.clear();
				}
			    return false;
			}
		};
		// disable text selection for non IE browsers
		return false;
	};


	/**
	 * ondblclick handler event handler.
	 * This event handler is attached to every DIV element in drag container (please see "enableDrag").
	 * @param {Event} e Event information.
	 * @see <a href="#enableDrag">enableDrag</a>
	 * @see <a href="#handlerOnMouseDown">handlerOnMouseDown</a>
	 * @see <a href="#add_events">add_events</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	handlerOnDblClick = function (e) {
		// call custom event handler
		REDIPS.drag.event.dblClicked();
	};

 
	/**
	 * Method sets current table group in "tables" array to the array top ("tables" array is sorted).
	 * The purpose is to enable tables nesting and to improve perfomance. Tables closer to the top of the array will be scanned before other tables in array.
	 * This method is called from "handlerOnMouseDown" on every DIV element click.
	 * DIV element belongs to the table and this table group ("ground" table + its nested tables) should go to the array top.
	 * @param {HTMLElement} obj Clicked DIV element (table of the clicked DIV element will be sorted to the array top).
	 * @see <a href="#handlerOnMouseDown">handlerOnMouseDown</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	tableTop = function (obj) {
		var	e,		// element
			i,		// loop variable
			tmp,	// temporary storage (needed for exchanging array members)
			group;	// tables group
		// find table for clicked DIV element
		e = findParent('TABLE', obj);
		// set tables group
		group = e.redips.nestedGroup;
		// set highest "redips.sort" parameter to the current table group
		for (i = 0; i < tables.length; i++) {
			// "ground" table is table with lowest level hierarchy and with its nested tables creates table group
			// nested table will be sorted before "ground" table
			if (tables[i].redips.nestedGroup === group) {
				tables[i].redips.sort = sortIdx * 100 + tables[i].redips.nestedLevel; // sort = sortIdx * 100 + level
			}
		}
		// sort "tables" array according to redips.sort (tables with higher redips.sort parameter will go to the array top)
		tables.sort(function (a, b) {
			return b.redips.sort - a.redips.sort;
		});
		// increase sortIdx
		sortIdx++;
	};


	/**
	 * Methods returns reference to the table row or clones table row.
	 * If called from handlerOnMouseDown:
	 * <ul>
	 * <li>input parameter is DIV class="row"</li>
	 * <li>method will return reference of the current row</li>
	 * </ul>
	 * If called from handlerOnMouseMove:
	 * <ul>
	 * <li>input parameter is TR (current row) - previously returned with this function</li>
	 * <li>method will clone current row and return reference of the cloned row</li>
	 * </ul>
	 * If called from moveObject:
	 * <ul>
	 * <li>input parameter is TR (row to animate)</li>
	 * <li>method will clone row and return reference of the cloned row</li>
	 * </ul> 
	 * @param {HTMLElement} el DIV class="row" or TR (current row)
	 * @param {String} [row_mode] If set to "animated" then search for last row will not include search for "rowhandler" DIV element.
	 * @return {HTMLElement} Returns reference of the current row or clone current row and return reference of the cloned row.
	 * @see <a href="#handlerOnMouseDown">handlerOnMouseDown</a>
	 * @see <a href="#handlerOnMouseMove">handlerOnMouseMove</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	rowClone = function (el, row_mode) {
		var tableMini,			// original table is cloned and all rows except picked row are deleted
			offset,				// offset of source TR
			rowObj,			// reference to the row object
			last_idx,			// last row index in cloned table
			emptyRow,			// (boolean) flag indicates if dragged row is last row and should be marked as "empty row"
			cr,					// current row (needed for searc if dragged row is last row)
			div,				// reference to the <DIV class="drag row"> element
			i, j;				// loop variables
		// 1) rowClone call in onmousedown will return reference of TR element (input parameter is HTMLElement <div class="drag row">)
		if (el.nodeName === 'DIV') {
			// remember reference to the <DIV class="drag row">
			div = el;
		    // find parent TR element
			el = findParent('TR', el);
			// create a "property object" in which all custom properties will be saved (it is only one property for now)
			if (el.redips === undefined) {
				el.redips = {};
			}
			// save reference to the DIV element as redips.div
			// this will mostly be referenced as objOld.redips.div (because objOld in row dragging context is reference to the source row)
			el.redips.div = div;
			// return reference to the TR element
			return el;
		}
		// 2) rowClone call in onmousemove will clone current row (el.nodeName === 'TR')
		else {
			// remember source row
			rowObj = el;
			// if redips object doesn't exist (possible if rowClone() is called from moveObject() method) then create initialize redips object on TR element
			if (rowObj.redips === undefined) {
				rowObj.redips = {};
			}
		    // find parent table
			el = findParent('TABLE', el);
			// before cloning, cut out "clone" class name from <div class="drag row clone"> element if needed
			if (cloneClass && cloned) {
				// set reference to the <div class="drag row clone"> element
				div = rowObj.redips.div;
				// no more cloning, cut "clone" from class names
				div.className = normalize(div.className.replace('clone', ''));
			}
			// clone whole table
			tableMini = el.cloneNode(true);
			// return "clone" to the source element
			if (cloneClass && cloned) {
				div.className = div.className + ' clone';
			}
			// find last row index in cloned table
			last_idx = tableMini.rows.length - 1;
			// if row mode is "animated" then definition of last row in table is simple
			if (row_mode === 'animated') {
				if (last_idx === 0) {
					emptyRow = true;
				}
				else {
					emptyRow = false;
				}
			}
			// else set initially emptyRow to true (it can be set to false in lower loop) 
			else {
				emptyRow = true;
			}
		    // test if dragged row is the last row and delete all rows but current row
			// the trick is to find rowhandler in cells except current cell and that's fine for user interface
			// if rows are animated, then "rowhandler" cells don't have to exsist and user should take care about marking last row as "empty row"
			for (i = last_idx; i >= 0; i--) {
				// if row is not the current row
				if (i !== rowObj.rowIndex) {
					// search for "rowhandler cell" in table row if row mode is not "animated" (user drags row)
					// this lines are skipped in case of animated mode (
					if (emptyRow === true && row_mode === undefined) {
						// set current row
						cr = tableMini.rows[i];
						// open loop to go through each cell
						for (j = 0; j < cr.cells.length; j++) {
							// if table cell contains "rowhandler" class name then dragged row is not the last row in table
							if (cr.cells[j].className.indexOf('rowhandler') > -1) {
								emptyRow = false;
								break;
							}
						}
						
					}
					// delete row (it should go after searching for "rowhandler" class name)
					tableMini.deleteRow(i);
				}
			}
			// if row is not cloned then set emptyRow property
			// cloned row always leaves original row in the table so emptyRow property should stay as it was before clone operation
			if (!cloned) {
				// set emptyRow flag to the current row
				// * needed in rowDrop() for replacing this row with dropped row
				// * needed in setTableRowColumn() to disable dropping DIV elements to the empty row
				rowObj.redips.emptyRow = emptyRow;
			}
			// create a "property object" in which all custom properties will be saved
			tableMini.redips = {};
			// set reference to the redips.container (needed if moveObject() moves elements in other container)
			tableMini.redips.container = el.redips.container;
			// save source row reference (needed for source row deletion in rowDrop method)
			tableMini.redips.sourceRow = rowObj;
			// set form values in cloned row (to prevent reset values of form elements)
			formElements(rowObj, tableMini.rows[0]);
			// copy custom properties to all child DIV elements and set onmousedown/ondblclick event handlers
			copyProperties(rowObj, tableMini.rows[0]);
			// append cloned mini table to the DIV id="redips_clone"
			document.getElementById('redips_clone').appendChild(tableMini);
			// include scroll position in offset
			offset = boxOffset(rowObj, 'fixed');
			// set position and position type
			tableMini.style.position = 'fixed';
			tableMini.style.top = offset[0] + "px";
			tableMini.style.left = offset[3] + "px";
			// define width of mini table
			tableMini.style.width = (offset[1] - offset[3]) + "px";
			// return reference of mini table
			return tableMini;
		}
	};


	/**
	 * Method drops table row to the target row and calls user event handlers. Source row is deleted and cloned row is inserted at the new position.
	 * Method takes care about the last row in the table only if user drags element. In case of moving rows with moveObject(), control
	 * and logic for last row is turned off. This method is called from handlerOnMouseUp() and animation().
	 * @param {Integer} tableIdx Table index.
	 * @param {Integer} rowIdx Row index.
	 * @param {HTMLElement} [tableMini] Reference to the mini table (table that contains only one row). This is actually clone of source row.
	 * @see <a href="#rowClone">rowClone</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	rowDrop = function (tableIdx, rowIdx, tableMini) {
		// local variables
		var animated = false,	// (boolean) flag shows if row is animated or dragged by user
			drop,				// (boolean) if false then dropping row will be canceled
			trMini,				// reference to the TR in mini table
			source = {},		// object contains: source table reference, source row reference, source row index and source table section (parent of row)
			target = {},		// object contains: target table reference, target row reference and target table section (parent of row)
			deleteTableRow;		// delete row (private method)
		// delete table row - input paremeter is row reference (private method)
		deleteTableRow = function (el) {
			var tbl;
			// if row doesn't have custom "redips" property or is not marked as empty, then it can be deleted
			if (el.redips === undefined || !el.redips.emptyRow) {
				tbl = findParent('TABLE', el);
				tbl.deleteRow(el.rowIndex);
			}
			// else, row is marked as "empty" and it will be only colored (not deleted)
			// content of table cells will be deleted and background color will be set to default color
			else {
				rowOpacity(el, 'empty', REDIPS.drag.style.rowEmptyColor);
			}
		};
		// if tableMini is not defined, then rowDrop() is called from handlerOnMouseUp() and set reference to the currently dragged row - mini table
		if (tableMini === undefined) {
			tableMini = obj;
		}
		// otherwise, rowDrop() is called from animation() (because third input parameter is set)
		// in that case set animated flag to true to turn off "last row" logic
		else {
			animated = true;
		}
		// define source data: row, row index, table and table section (needed for "switch" rowDropMode)
		source.row = tableMini.redips.sourceRow;
		source.rowIndex = source.row.rowIndex;
		source.table = findParent('TABLE', source.row);
		source.tableSection = source.table.rows[0].parentNode;
		// define target data: row, row index, table and table section
		target.table = tables[tableIdx];
		target.row = target.table.rows[rowIdx];
		target.rowIndex = rowIdx;
		target.tableSection = target.table.rows[0].parentNode;
		// set reference to the TR in mini table (mini table has only one row - first row)
		trMini = tableMini.getElementsByTagName('tr')[0];
		// destroy mini table (node still exists in memory)
		tableMini.parentNode.removeChild(tableMini);
		// call event.rowDroppedBefore() - this handler can return "false" value
		drop = REDIPS.drag.event.rowDroppedBefore(source.table, source.rowIndex);
		// if handler returned false then row dropping will be canceled
		if (drop !== false) {
			// row is moved to the "trash"
			if (!animated && td.target.className.indexOf(REDIPS.drag.trash.className) > -1) {
				// test if cloned row is directly dropped to the "trash" cell (call rowDeleted event handler)
				if (cloned) {
					REDIPS.drag.event.rowDeleted();
				}
				// row is not cloned
				else {
					// if trash.questionRow is set then user should should confirm delete row action
					if (REDIPS.drag.trash.questionRow) {
						// ask user if is sure
						if (confirm(REDIPS.drag.trash.questionRow)) {
							// delete source row and call rowDeleted event handler
							deleteTableRow(source.row);
							REDIPS.drag.event.rowDeleted();
						}
						// user is not sure - undelete
						else {
							// delete emptyRow property from source row because emptyRow will be set on next move
							// otherwise row would be overwritten and that's no good
							delete objOld.redips.emptyRow;
							// just call undeleted handler
							REDIPS.drag.event.rowUndeleted();
						}
					}
					// trask_ask_row is set to "false" - source row can be deleted
					else {
						// delete source row and call rowDeleted event handler
						deleteTableRow(source.row);
						REDIPS.drag.event.rowDeleted();
					}
				}
			}
			// normal row move
			else {
				// if row is not dropped to the last row position
				if (target.rowIndex < target.table.rows.length) {
					// if source and target rows are from the same table
					if (table === table_source) {
						// row is dropped above source position from the same table
						if (source.rowIndex > target.rowIndex) {
							target.tableSection.insertBefore(trMini, target.row);
						}
						// row is dropped to the lower position in the same table
						else {
							target.tableSection.insertBefore(trMini, target.row.nextSibling);
						}
					}
					// row is dropped to other table and will be placed after highlighted row
					else if (REDIPS.drag.rowDropMode === 'after') {
						target.tableSection.insertBefore(trMini, target.row.nextSibling);
					}
					// row is dropped to other table and will be placed before highlighted row
					// this code will be executed in case of "before", "switch" and "overwrite" row drop mode (when dropping row to other table)
					else {
						target.tableSection.insertBefore(trMini, target.row);
					}
				}
				// row is dropped to the last row position
				// it's possible to set target row index greater then number of rows - in this case row will be appended to the table end
				else {
					// row should be appended
					target.tableSection.appendChild(trMini);
					// set reference to the upper row
					// after row is appended, upper row should be tested if contains "emptyRow" set to true  
					// this could happen in case when row is moved to the table with only one empty row
					target.row = target.table.rows[0];
				}
				// if table contains only "empty" row then this row should be deleted after inserting or appending to such table
				if (target.row && target.row.redips && target.row.redips.emptyRow) {
					target.tableSection.deleteRow(target.row.rowIndex);
				}
				// in case of "overwrite", delete target row
				else if (REDIPS.drag.rowDropMode === 'overwrite') {
					deleteTableRow(target.row);
				}
				// insert target row to source location and delete source row (if row is not cloned)
				else if (REDIPS.drag.rowDropMode === 'switch' && !cloned) {
					source.tableSection.insertBefore(target.row, source.row);
					// delete emptyRow flag to the source row (needed in case when switching last row from table2 to table1)
					if (source.row.redips !== undefined) {
						delete source.row.redips.emptyRow;
					}
				}
				// delete source row if called from animation() or row is not cloned
				if (animated || !cloned) {
					deleteTableRow(source.row);
				}
				// delete emptyRow property from inserted/appended row because emptyRow will be set on next move
				// copyProperties() in rowClone() copied emptyRow property to the row in tableMini
				// otherwise row will be overwritten and that is not good
				delete trMini.redips.emptyRow;
				// call rowDropped event handler if rowDrop() was not called from animation()
				if (!animated) {
					REDIPS.drag.event.rowDropped(trMini, source.table, source.rowIndex);
				}
			} // end normal row move
			// if row contains TABLE(S) then recall initTables() to properly initialize tables array and set custom properties
			// no matter if row was moved or deleted
			if (trMini.getElementsByTagName('table').length > 0) {
				initTables();
			}
		}
		// event.rowDroppedBefore() returned "false" (it's up to user to return source row opacity to its original state) 
		else {
			// rowOpacity(objOld, 100);
		}
	};


	/**
	 * Method sets form values after cloning table row. Method is called from rowClone.
	 * cloneNode() should take care about form values when performing deep cloning - but some browsers have a problem.
	 * This method will fix checkboxes, selected indexes and so on when dragging table row (values in form elements will be preserved).
	 * @param {HTMLElement} str Source table row.
	 * @param {HTMLElement} ctr Cloned table row. Table row is cloned in a moment of dragging.
	 * @see <a href="#rowClone">rowClone</a>
	 * @private
	 * @memberOf REDIPS.drag# 
	 */
	formElements = function (str, ctr) {
		// local variables
		var i, j, k, type,
			src = [],	// collection of form elements from source row
			cld = [];	// collection of form elements from cloned row
		// collect form elements from source row
		src[0] = str.getElementsByTagName('input');
		src[1] = str.getElementsByTagName('textarea');
		src[2] = str.getElementsByTagName('select');
		// collect form elements from cloned row
		cld[0] = ctr.getElementsByTagName('input');
		cld[1] = ctr.getElementsByTagName('textarea');
		cld[2] = ctr.getElementsByTagName('select');
		// loop through found form elements in source row
		for (i = 0; i < src.length; i++) {
			for (j = 0; j < src[i].length; j++) {
				// define element type
				type = src[i][j].type;
				switch (type) {
				case 'text':
				case 'textarea':
				case 'password':
					cld[i][j].value = src[i][j].value;
					break;
				case 'radio':
				case 'checkbox':
					cld[i][j].checked = src[i][j].checked;
					break;
				case 'select-one':
					cld[i][j].selectedIndex = src[i][j].selectedIndex;
					break;
				case 'select-multiple':
					for (k = 0; k < src[i][j].options.length; k++) {
						cld[i][j].options[k].selected = src[i][j].options[k].selected;
					}
					break;
				} // end switch
			} // end for j
		} // end for i
	};


	/**
	 * onmouseup event handler.
	 * handlerOnMouseUp is attached to the DIV element in a moment when DIV element is clicked (this happens in handlerOnMouseDown).
	 * This event handler detaches onmousemove and onmouseup event handlers.
	 * @param {Event} e Event information.
	 * @see <a href="#handlerOnMouseDown">handlerOnMouseDown</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	handlerOnMouseUp = function (e) {
		var evt = e || window.event,	// define event (FF & IE)
			target_table,				// needed for test if cloned element is dropped outside table
			r_table, r_row,				// needed for mode="row"
			mt_tr,						// needed for returning color to the table cell (mt_tr - "mini table" "table_row")
			X, Y,						// X and Y position of mouse pointer
			i,							// used in local loop
			drop,						// if false then dropped DIV element (in case of dropMode="switch") will be canceled
			// define target elements and target elements length needed for switching table cells
			// target_elements_length is needed because nodeList objects in the DOM are live 
			// please see http://www.redips.net/javascript/nodelist-objects-are-live/
			target_elements, target_elements_length;
		// define X and Y position
		X = evt.clientX;
		Y = evt.clientY;
		// turn off autoscroll "current cell" handling (if user mouseup in the middle of autoscrolling)
		edge.flag.x = edge.flag.y = 0;
		// remove mouse capture from the object in the current document
		// get IE (all versions) to allow dragging outside the window (?!)
		// http://stackoverflow.com/questions/1685326/responding-to-the-onmousemove-event-outside-of-the-browser-window-in-ie
		if (obj.releaseCapture) {
			obj.releaseCapture();
		}
		// detach mousemove and touchmove event handlers on document object
		REDIPS.event.remove(document, 'mousemove', handlerOnMouseMove);
		REDIPS.event.remove(document, 'touchmove', handlerOnMouseMove);
		// detach mouseup and touchend event handlers on document object
		REDIPS.event.remove(document, 'mouseup', handlerOnMouseUp);
		REDIPS.event.remove(document, 'touchend', handlerOnMouseUp);
		// detach dragContainer.onselectstart handler to enable select for IE7/IE8 browser 
		dragContainer.onselectstart = null;
		// reset object styles
		resetStyles(obj);
		// document.body.scroll... only works in compatibility (aka quirks) mode,
		// for standard mode, use: document.documentElement.scroll...
		scrollData.width  = document.documentElement.scrollWidth;
		scrollData.height = document.documentElement.scrollHeight;	
		// reset autoscroll flags
		edge.flag.x = edge.flag.y = 0;
		// this could happen if "clone" element is placed inside forbidden table cell
		if (cloned && mode === 'cell' && (table === null || row === null || cell === null)) {
			obj.parentNode.removeChild(obj);
			// decrease clonedId counter
			clonedId[objOld.id] -= 1;
			REDIPS.drag.event.notCloned();
		}
		// if DIV element was clicked and left button was released, but element is placed inside unmovable table cell
		else if (table === null || row === null || cell === null) {
			REDIPS.drag.event.notMoved();
		}		
		else {
			// if current table is in range, use table for current location
			if (table < tables.length) {
				target_table = tables[table];
				REDIPS.drag.td.target = td.target = target_table.rows[row].cells[cell];
				// set background color for destination cell (cell had hover color)
				setTdStyle(table, row, cell, bgStyleOld);
				// set r_table & r_row (needed for mode === "row")
				r_table = table;
				r_row = row;
			}
			// if any level of old position is undefined, then use source location
			else if (table_old === null || row_old === null || cell_old === null) {
				target_table = tables[table_source];
				REDIPS.drag.td.target = td.target = target_table.rows[row_source].cells[cell_source];
				// set background color for destination cell (cell had hover color)
				setTdStyle(table_source, row_source, cell_source, bgStyleOld);
				// set r_table & r_row (needed for mode === "row")
				r_table = table_source;
				r_row = row_source;
			}
			// or use the previous location
			else {
				target_table = tables[table_old];
				REDIPS.drag.td.target = td.target = target_table.rows[row_old].cells[cell_old];
				// set background color for destination cell (cell had hover color)
				setTdStyle(table_old, row_old, cell_old, bgStyleOld);
				// set r_table & r_row (needed for mode === "row")
				r_table = table_old;
				r_row = row_old;
			}
			// if dragging mode is table row
			if (mode === 'row') {
				// row was clicked and mouse button was released right away (row was not moved)
				if (!moved) {
					REDIPS.drag.event.rowNotMoved();
				}
				// row was moved
				else {
					// and dropped to the source row
					if (table_source === r_table && row_source === r_row) {
						// reference to the TR in mini table (mini table has only one row)
						mt_tr = obj.getElementsByTagName('tr')[0];
						// return color to the source row from the row of cloned mini table
						// color of the source row can be changed in event.rowMoved() (when user wants to mark source row)
						objOld.style.backgroundColor = mt_tr.style.backgroundColor;
						// return color to the each table cell
						for (i = 0; i < mt_tr.cells.length; i++) {
							objOld.cells[i].style.backgroundColor = mt_tr.cells[i].style.backgroundColor;
						}
						// remove cloned mini table
						obj.parentNode.removeChild(obj);
						// delete emptyRow property from source row because emptyRow will be set on next move
						// otherwise row would be overwritten and that's no good
						delete objOld.redips.emptyRow;
						// if row was cloned and dropped to the source location then call rowNotCloned event handler
						if (cloned) {
							REDIPS.drag.event.rowNotCloned();
						}
						// call event.rowDroppedSource() event handler
						else {
							REDIPS.drag.event.rowDroppedSource(td.target);
						}
					}
					// and dropped to the new row
					else {
						rowDrop(r_table, r_row);
					}	
				}
			}
			// clicked element was not moved - DIV element didn't cross threshold value
			// just call event.notMoved event handler
			else if (!cloned && !threshold.flag) {
				REDIPS.drag.event.notMoved();
			}
			// delete cloned element if dropped on the start position
			else if (cloned && table_source === table && row_source === row && cell_source === cell) {
				obj.parentNode.removeChild(obj);
				// decrease clonedId counter
				clonedId[objOld.id] -= 1;
				REDIPS.drag.event.notCloned();
			}
			// delete cloned element if dropped outside current table and clone.drop is set to false
			else if (cloned && REDIPS.drag.clone.drop === false &&
					(X < target_table.redips.offset[3] || X > target_table.redips.offset[1] ||
					Y < target_table.redips.offset[0] || Y > target_table.redips.offset[2])) {
				obj.parentNode.removeChild(obj);
				// decrease clonedId counter
				clonedId[objOld.id] -= 1;
				REDIPS.drag.event.notCloned();
			}
			// remove object if destination cell has "trash" in class name
			else if (td.target.className.indexOf(REDIPS.drag.trash.className) > -1) {
				// remove child from DOM (node still exists in memory)
				obj.parentNode.removeChild(obj);
				// if public property trash.question is set then ask for confirmation
				if (REDIPS.drag.trash.question) {
					setTimeout(function () {
						// Are you sure?
						if (confirm(REDIPS.drag.trash.question)) {
							// yes, do all actions needed after element is deleted
							elementDeleted();
						}
						// no, do undelete
						else {
							// undelete DIV element
							if (!cloned) {
								// append removed object to the source table cell
								tables[table_source].rows[row_source].cells[cell_source].appendChild(obj);
								// and recalculate table cells because undelete can change row dimensions 
								calculateCells();
							}
							// call undeleted event handler
							REDIPS.drag.event.undeleted();	
						}
					}, 20);
				}
				// element is deleted and do all actions needed after element is deleted
				else {
					elementDeleted();
				}
			}
			else if (REDIPS.drag.dropMode === 'switch') {
				// call event.droppedBefore event handler
				drop = REDIPS.drag.event.droppedBefore(td.target);
				// if returned value is false then only call elementDrop with input parameter "false" to delete cloned element (if needed)
				// dragged DIV element will be returned to the initial position
				if (drop === false) {
					elementDrop(false);
				}
				// normal procedure for "switch" drag option
				else {
					// remove dragged element from DOM (source cell) - node still exists in memory
					obj.parentNode.removeChild(obj);
					// move object from the destination to the source cell
					target_elements = td.target.getElementsByTagName('div');
					target_elements_length = target_elements.length;
					for (i = 0; i < target_elements_length; i++) {
						// sourceCell is defined in onmouseup
						if (target_elements[0] !== undefined) { //fixes issue with nested DIVS
							// save reference of switched element in REDIPS.drag.objOld property
							// '0', not 'i' because NodeList objects in the DOM are live
							REDIPS.drag.objOld = objOld = target_elements[0];
							// append objOld to the source cell
							td.source.appendChild(objOld);
							// register event listeners (FIX for Safari Mobile)
							// it seems that Safari Mobile loses registrated events (traditional model) assigned to the DIV element (other browsers work just fine without this line)
							registerEvents(objOld);
						}
					}
					// elementDrop() should be called before event.switched() otherwise targetCell will be undefined
					elementDrop();
					// if destination element exists, then elements will be switched
					if (target_elements_length) {
						// call event.switched because cloneLimit could call event.clonedEnd1 or event.clonedEnd2
						REDIPS.drag.event.switched();
					}
				}
			}
			// overwrite destination cell with dragged content 
			else if (REDIPS.drag.dropMode === 'overwrite') {
				// call event.droppedBefore event handler
				drop = REDIPS.drag.event.droppedBefore(td.target);
				// if event handler didn't return "false" then proceed normaly (otherwise dropped element will be returned to the source table cell)
				if (drop !== false) {
					// empty target cell
					emptyCell(td.target);
				}
				// drop element to the table cell (or delete cloned element if drop="false")
				elementDrop(drop);
			}
			// else call event.droppedBefore(), append object to the cell and call event.dropped() 
			else {
				// call event.droppedBefore event handler
				drop = REDIPS.drag.event.droppedBefore(td.target);
				// drop element to the table cell (or delete cloned element if drop="false")
				elementDrop(drop);
			}
			// force naughty browsers (IE6, IE7 ...) to redraw source and destination row (element.className = element.className does the trick)
			// but careful (table_source || row_source could be null if clone element was clicked in denied table cell)
			//
			// today we are in era of FF5, IE9 ... so maybe this lines were not needed any more (first I will comment them out and if nobody will complain
			// then they will be deleted completely)
			/*
			if (table_source !== null && row_source !== null && tables[table_source].rows[row_source] !== undefined) {
				tables[table_source].rows[row_source].className = tables[table_source].rows[row_source].className;
			}
			targetCell.parentNode.className = targetCell.parentNode.className;
			*/
			// if dropped object contains TABLE(S) then recall initTables() to properly initialize tables array (only in cell mode)
			// if row is dragged and contains tables, then this will be handler in rowDrop() private method
			if (mode === 'cell' && obj.getElementsByTagName('table').length > 0) {
				initTables();
			}
			// recalculate table cells and scrollers because cell content could change row dimensions 
			calculateCells();
			// call last event handler
			REDIPS.drag.event.finish();
		}
		// reset old positions
		table_old = row_old = cell_old = null;
	};


	/**
	 * Element drop. This method is called from handlerOnMouseUp and appends element to the target table cell.
	 * If input parameter "drop" is set to "false" (this is actually return value from event.droppedBefore) then DIV elements will not be dropped (only cloned element will be deleted).
	 * @param {Boolean} [drop] If not "false" then DIV element will be dropped to the cell.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	elementDrop = function (drop) {
		var cloneSourceDiv = null,	// clone source element (needed if clone.sendBack is set to true)
			div,					// nodeList of DIV elements in target cell (needed if clone.sendBack is set to true)
			i;						// local variables
		// if input parameter is not "false" then DIV element will be dropped to the table cell
		if (drop !== false) {
			// if clone.sendBack is set to true then try to find source element in target cell
			if (clone.sendBack === true) {
				// search all DIV elements in target cell
				div = td.target.getElementsByTagName('DIV');
				// loop through all DIV elements in target cell
				for (i = 0; i < div.length; i++) {
					// if DIV in target cell is source of dropped DIV element (dropped DIV id and id of DIV in target cell has the same name beginning like "d12c2" and "d12")
					// of course, the case where source DIV element is dropped to the cell with cloned DIV element should be excluded (possible in climit1 type)
					if (obj !== div[i] && obj.id.indexOf(div[i].id) === 0) {
						// set reference to cloneSourceDiv element
						cloneSourceDiv = div[i];
						// break the loop
						break;
					}
				}
				// if clone source DIV element exists in target cell
				if (cloneSourceDiv) {
					// update climit class (increment by 1)
					cloneLimit(cloneSourceDiv, 1);
					// delete dropped DIV element
					obj.parentNode.removeChild(obj);
					// return from the method (everything is done)
					return;
				}
			}
			// shift table content if dropMode is set to "shift" and target cell is not empty or shift.after option is set to always
			// hasChild() is a private method
			if (REDIPS.drag.dropMode === 'shift' && (hasChilds(td.target) || REDIPS.drag.shift.after === 'always')) {
				shiftCells(td.source, td.target);
			}
			// insert (to top) or append (to bottom) object to the target cell
			if (REDIPS.drag.multipleDrop === 'top' && td.target.hasChildNodes()) {
				td.target.insertBefore(obj, td.target.firstChild);
			}
			else {
				td.target.appendChild(obj);
			}
			// register event listeners (FIX for Safari Mobile)
			registerEvents(obj);
			// call event.dropped because cloneLimit could call event.clonedEnd1 or event.clonedEnd2
			REDIPS.drag.event.dropped(td.target);
			// if object is cloned
			if (cloned) {
				// call clonedDropped event handler
				REDIPS.drag.event.clonedDropped(td.target);
				// update climit1_X or climit2_X classname
				cloneLimit(objOld, -1);
			}
		}
		// cloned element should be deleted (if not already deleted)
		else if (cloned && obj.parentNode) {
			obj.parentNode.removeChild(obj);
		}
	};


	/**
	 * Register event listeners for DIV element.
	 * DIV elements should have only onmousedown, ontouchstart and ondblclick attached (using traditional event registration model).
	 * I had a problem with advanced event registration model.
	 * In case of using advanced model, selected text and dragged DIV element were in collision.
	 * It looks like selected text was able to drag instead of DIV element.
	 * @param {HTMLElement} div Register event listeners for onmousedown, ontouchstart and ondblclick to the DIV element.
	 * @param {Boolean} [flag] If set to false then event listeners will be deleted.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	registerEvents = function (div, flag) {
		// if flag is se to false, then remove event listeners on DIV element
		if (flag === false) {
			div.onmousedown = null;
			div.ontouchstart = null;
			div.ondblclick = null;
		}
		else {
			div.onmousedown = handlerOnMouseDown;
			div.ontouchstart = handlerOnMouseDown;
			div.ondblclick = handlerOnDblClick;
		}
	};

	
	/**
	 * After element is dropped, styles need to be reset.
	 * @param {HTMLElement} el Element reference.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	resetStyles = function (el) {
		// reset top and left styles
		el.style.top  = '';
		el.style.left = '';
		// reset position and z-index style (if not set or default value for position style is "static")
		el.style.position = '';
		el.style.zIndex = '';
	};

	
	/**
	 * Actions needed after element is deleted. This function is called from handlerOnMouseUp. Function deletes element and calls event handlers.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	elementDeleted = function () {
		// set param needed to find last cell (for case where shift.after is 'always' or 'delete')
		var param;
		// if object is cloned, update climit1_X or climit2_X classname
		if (cloned) {
			cloneLimit(objOld, -1);
		}
		// shift table content if dropMode is set to "shift" and shift.after is set to "delete" or "always"
		if (REDIPS.drag.dropMode === 'shift' && (REDIPS.drag.shift.after === 'delete' || REDIPS.drag.shift.after === 'always')) {
			// define last table cell in column, row or table - depending on shift.mode value
			switch (REDIPS.drag.shift.mode) {
			case 'vertical2':
				param = 'lastInColumn';
				break;
			case 'horizontal2':
				param = 'lastInRow';
				break;
			default:
				param = 'last';
			}
			// content from source cell to last cell will be shifted (emulates dropping DIV element to the last table cell)
			shiftCells(td.source, findCell(param, td.source)[2]);
		}
		// call event.deleted() method and send cloned flag
		// inside event.deleted it's possible to know whether cloned element is directly moved to the trash
		REDIPS.drag.event.deleted(cloned);
	};


	/**
	 * onmousemove event handler.
	 * handlerOnMouseMove is attached to document level in a moment when DIV element is clicked (this happens in handlerOnMouseDown).
	 * handlerOnMouseUp detaches onmousemove and onmouseup event handlers.
	 * @param {Event} e Event information.
	 * @see <a href="#handlerOnMouseDown">handlerOnMouseDown</a>
	 * @see <a href="#handlerOnMouseUp">handlerOnMouseUp</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	handlerOnMouseMove = function (e) {
		var evt = e || window.event,			// define event (FF & IE)
			bound = REDIPS.drag.scroll.bound,	// read "bound" public property (maybe code will be faster, and it will be easier to reference in onmousemove handler)
			sca,								// current scrollable container area
			X, Y,								// X and Y position of mouse pointer
			deltaX, deltaY,						// delta from initial position
			i,									// needed for local loop
			scrollPosition;						// scroll position variable needed for autoscroll call
		// define X and Y position (pointer.x and pointer.y are needed in setTableRowColumn() and autoscroll methods) for touchscreen devices
		if (evt.touches) {
			X = pointer.x = evt.touches[0].clientX;
			Y = pointer.y = evt.touches[0].clientY;
		}
		// or for monitor + mouse devices
		else {
			X = pointer.x = evt.clientX;
			Y = pointer.y = evt.clientY;
		}
		// calculate delta from initial position
		deltaX = Math.abs(threshold.x - X);
		deltaY = Math.abs(threshold.y - Y);
		// if "moved" flag isn't set (this is the first moment when object is moved)
		if (!moved) {
			// if moved object is element and has clone in class name or cloneShiftKey is enabled and shift key is pressed
			// then remember previous object, clone object, set cloned flag and call event.cloned
			// (shiftKey is defined in handler_mousedown)
			if (mode === 'cell' && (cloneClass || (REDIPS.drag.clone.keyDiv === true && shiftKey))) {
				// remember previous object (original element)
				REDIPS.drag.objOld = objOld = obj;
				// clone DIV element ready for dragging
				REDIPS.drag.obj = obj = cloneObject(obj, true);
				// set cloned flag
				cloned = true;
				// call event.cloned event handler
				REDIPS.drag.event.cloned();
				// set color for the current table cell and remember previous position and color
				setPosition();
			}
			// else ordinary object is moved
			else {
				// if mode is row then remember reference of the source row, clone source row and set obj as reference to the current row
				if (mode === 'row') {
					// settings of "cloned" flag should go before calling rowClone() because "cloned" is needed in rowClone()
					// to cut out "clone" class name from <div class="drag row clone"> elements
					if (cloneClass || (REDIPS.drag.clone.keyRow === true && shiftKey)) {
						cloned = true;
					}
					// remember reference to the source row
					REDIPS.drag.objOld = objOld = obj;
					// clone source row and set as obj
					REDIPS.drag.obj = obj = rowClone(obj);
					// set high z-index for cloned mini table
					obj.style.zIndex = 999;
				}
				// get IE (all versions) to allow dragging outside the window (?!)
				// this was needed here also - despite setCaputure in onmousedown
				if (obj.setCapture) {
					obj.setCapture();
				}
				// set style to fixed to allow dragging DIV object
				obj.style.position = 'fixed';
				// call calculate cells for case where moved element changed cell dimension
				// place 3 elements in the same cell in example08 and try to move one out of the table cell
				calculateCells();
				// set current table, row and column
				setTableRowColumn();
				// call event handler (row cloned/moved)
				if (mode === 'row') {
					if (cloned) {
						REDIPS.drag.event.rowCloned();
					}
					else {
						REDIPS.drag.event.rowMoved();
					}
				}
				// set color for the current table cell and remember previous position and color
				// setPosition() must go after calling event.moved() and event.rowMoved() if user wants to
				// change color of source row
				setPosition();
			}
			// if element is far away on the right side of page, set possible right position (screen.width - object width)
			// objMargin[1] + objMargin[3] = object width
			if (X > screen.width - objMargin[1]) {
				obj.style.left = (screen.width - (objMargin[1] +  objMargin[3])) + 'px';
			}
			// if element is below page bottom, set possible lower position (screen.width - object height)
			// objMargin[0] + objMargin[2] = object height
			if (Y > screen.height - objMargin[2]) {
				obj.style.top  = (screen.height - (objMargin[0] + objMargin[2])) + 'px';
			}
		}
		// set moved_flag
		moved = true;
		// if REDIPS.drag works in "cell" mode and DIV element is moved out of defined threshold distance 
		if (mode === 'cell' && (deltaX > threshold.value || deltaY > threshold.value) && !threshold.flag) {
			// set threshold flag
			threshold.flag = true;
			// set position (highlight current position)
			setPosition();
			// call event.moved with cloned as input parameter
			REDIPS.drag.event.moved(cloned);
		}
		// set left and top styles for the moved element if element is inside window
		// this conditions will stop element on window bounds
		if (X > objMargin[3] && X < screen.width - objMargin[1]) {
			obj.style.left = (X - objMargin[3]) + 'px';
		}
		if (Y > objMargin[0] && Y < screen.height - objMargin[2]) {
			obj.style.top  = (Y - objMargin[0]) + 'px';
		}
		// set current table, row and cell (this condition should spare CPU):
		// 1) if mouse pointer is inside DIV id="drag"
		// 2) and autoscroll is not working
		// 3) and current table contains nested table or cursor is outside of current cell
		if (X < divBox[1] && X > divBox[3] && Y < divBox[2] && Y > divBox[0] &&
			edge.flag.x === 0 && edge.flag.y === 0 &&
			(currentCell.containTable || (X < currentCell[3] || X > currentCell[1] || Y < currentCell[0] || Y > currentCell[2]))) {
			// set current table row and table cell
			setTableRowColumn();
			// if new location is inside table and new location is different then old location
			cellChanged();
		}
		// if autoScroll option is enabled (by default it is but it can be turned off)
		if (REDIPS.drag.scroll.enable) {
			// calculate horizontally crossed page bound
			edge.page.x = bound - (screen.width / 2  > X ? X - objMargin[3] : screen.width - X - objMargin[1]);
			// if element crosses page bound then set scroll direction and call auto scroll 
			if (edge.page.x > 0) {
				// in case when object is only half visible
				if (edge.page.x > bound) {
					edge.page.x = bound;
				}
				// get horizontal window scroll position
				scrollPosition = getScrollPosition()[0];
				// set scroll direction
				edge.page.x *= X < screen.width / 2 ? -1 : 1;
				// if page bound is crossed and this two cases aren't met:
				// 1) scrollbar is on the left and user wants to scroll left
				// 2) scrollbar is on the right and user wants to scroll right
				if (!((edge.page.x < 0 && scrollPosition <= 0) || (edge.page.x > 0 && scrollPosition >= (scrollData.width - screen.width)))) {
					// fire autoscroll function (this should happen only once)
					if (edge.flag.x++ === 0) {
						// reset onscroll event
						REDIPS.event.remove(window, 'scroll', calculateCells);
						// call window autoscroll 
						autoScrollX(window);
					}
				}
			}
			else {
				edge.page.x = 0;
			}
			// calculate vertically crossed page bound
			edge.page.y = bound - (screen.height / 2 > Y ? Y - objMargin[0] : screen.height - Y - objMargin[2]);
			// if element crosses page bound
			if (edge.page.y > 0) {
				// set max crossed bound
				if (edge.page.y > bound) {
					edge.page.y = bound;
				}
				// get vertical window scroll position
				scrollPosition = getScrollPosition()[1];
				// set scroll direction
				edge.page.y *= Y < screen.height / 2 ? -1 : 1;
				// if page bound is crossed and this two cases aren't met:
				// 1) scrollbar is on the page top and user wants to scroll up
				// 2) scrollbar is on the page bottom and user wants to scroll down
				if (!((edge.page.y < 0 && scrollPosition <= 0) || (edge.page.y > 0 && scrollPosition >= (scrollData.height - screen.height)))) {
					// fire autoscroll (this should happen only once)
					if (edge.flag.y++ === 0) {
						// reset onscroll event
						REDIPS.event.remove(window, 'scroll', calculateCells);
						// call window autoscroll
						autoScrollY(window);
					}
				}
			}
			else {
				edge.page.y = 0;
			}
			// test if dragged object is in scrollable container
			// this code will be executed only if scrollable container (DIV with overflow other than 'visible) exists on page
			for (i = 0; i < scrollData.container.length; i++) {
				// set current scrollable container area
				sca = scrollData.container[i];
				// if dragged object is inside scrollable container and scrollable container has enabled autoscroll option
				if (sca.autoscroll && X < sca.offset[1] && X > sca.offset[3] && Y < sca.offset[2] && Y > sca.offset[0]) {
					// calculate horizontally crossed page bound
					edge.div.x = bound - (sca.midstX  > X ? X - objMargin[3] - sca.offset[3] : sca.offset[1] - X - objMargin[1]);
					// if element crosses page bound then set scroll direction and call auto scroll 
					if (edge.div.x > 0) {
						// in case when object is only half visible (page is scrolled on that object)
						if (edge.div.x > bound) {
							edge.div.x = bound;
						}
						// set scroll direction: negative - left, positive - right
						edge.div.x *= X < sca.midstX ? -1 : 1; 
						// remove onscroll event handler and call autoScrollY function only once
						if (edge.flag.x++ === 0) {
							REDIPS.event.remove(sca.div, 'scroll', calculateCells);
							autoScrollX(sca.div);
						}
					}
					else {
						edge.div.x = 0;
					}
					// calculate vertically crossed page bound
					edge.div.y = bound - (sca.midstY  > Y ? Y - objMargin[0] - sca.offset[0] : sca.offset[2] - Y - objMargin[2]);
					// if element crosses page bound then set scroll direction and call auto scroll
					if (edge.div.y > 0) {
						// in case when object is only half visible (page is scrolled on that object)
						if (edge.div.y > bound) {
							edge.div.y = bound;
						}
						// set scroll direction: negative - up, positive - down
						edge.div.y *= Y < sca.midstY ? -1 : 1;
						// remove onscroll event handler and call autoScrollY function only once
						if (edge.flag.y++ === 0) {
							REDIPS.event.remove(sca.div, 'scroll', calculateCells);
							autoScrollY(sca.div);
						}
					}
					else {
						edge.div.y = 0;
					}
					// break the loop (checking for other scrollable containers is not needed) 
					break;
				}
				// otherwise (I mean dragged object isn't inside any of scrollable container) reset crossed edge
				else {
					edge.div.x = edge.div.y = 0;
				} 
			}
		} // if autoScroll is enabled
		// stop all propagation of the event in the bubbling phase.
		// (save system resources by turning off event bubbling / propagation)
		evt.cancelBubble = true;
		if (evt.stopPropagation) {
			evt.stopPropagation();
		}
	};


	/**
	 * This method is called (from handlerOnMouseMove, autoScrollX, autoScrollY) in case of change of current table cell.
	 * When change happens, then return background color to old position, highlight new position, calculate cell boundaries and call event.changed.
	 * @see <a href="#handlerOnMouseMove">handlerOnMouseMove</a>
	 * @see <a href="#autoScrollX">autoScrollX</a>
	 * @see <a href="#autoScrollY">autoScrollY</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	cellChanged = function () {
		if (table < tables.length && (table !== table_old || row !== row_old || cell !== cell_old)) {
			// set cell background color to the previous cell
			if (table_old !== null && row_old !== null && cell_old !== null) {
				// set background color for previous table cell
				setTdStyle(table_old, row_old, cell_old, bgStyleOld);
				// define previous table cell
				REDIPS.drag.td.previous = td.previous = tables[table_old].rows[row_old].cells[cell_old];
				// define current table cell
				REDIPS.drag.td.current = td.current = tables[table].rows[row].cells[cell];
				// if drop option is 'switching' and drag mode is 'cell' (not 'row')
				// then replace content from current cell to the previous cell
				if (REDIPS.drag.dropMode === 'switching' && mode === 'cell') {
					// move objects from current cell to the previous cell
					relocate(td.current, td.previous);
					// recalculate table cells again (because cell content could change row dimensions) 
					calculateCells();
					// set current table cell again (because cell content can be larger then cell itself)
					setTableRowColumn();
				}
				// target cell changed - call event.changed handler 
				if (mode === 'cell') {
					REDIPS.drag.event.changed(td.current);
				}
				// for mode === 'row', table or row should change (changing cell in the same row will be ignored)
				else if (mode === 'row' && (table !== table_old || row !== row_old)) {
					REDIPS.drag.event.rowChanged(td.current);
				}
			}
			// set color for the current table cell and remembers previous position and color
			setPosition();
		}		
	};


	/**
	 * In initialization phase, this method is attached as onresize event handler for window.
	 * It also calculates window width and window height. Result is saved in variables screen.width and screen.height visible inside REDIPS.drag private scope.
	 * @see <a href="#init">init</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	handlerOnResize = function () {
		// Non-IE
		if (typeof(window.innerWidth) === 'number') {
			screen.width  = window.innerWidth;
			screen.height = window.innerHeight;
		}
		// IE 6+ in 'standards compliant mode'
		else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
			screen.width  = document.documentElement.clientWidth;
			screen.height = document.documentElement.clientHeight;
		}
		// IE 4 compatible
		else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
			screen.width  = document.body.clientWidth;
			screen.height = document.body.clientHeight;
		}
		// set scroll size (onresize, onload and onmouseup event)
		scrollData.width  = document.documentElement.scrollWidth;
		scrollData.height = document.documentElement.scrollHeight;
		// calculate colums and rows offset (cells dimensions)
		calculateCells();  
	};


	/**
	 * Method sets current table, row and cell.
	 * Current cell position is based on position of mouse pointer and calculated grid of tables inside drag container.
	 * Method contains logic for dropping rules like marked/forbidden table cells.
	 * Rows with display='none' are not contained in row_offset array so row bounds calculation should take care about sparse arrays (since version 4.3.6).
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	setTableRowColumn = function () {
		var previous,	// set previous position (current cell will not be highlighted) 
			cell_current,	// define current cell (needed for some test at the function bottom)
			row_offset,		// row offsets for the selected table (row box bounds)
			row_found,		// remember found row
			cells,			// number of cells in the selected row
			empty,			// (boolean) flag indicates if table cell is empty or not
			mark_found,		// (boolean) found "mark" class name
			only_found,		// (boolean) found "only" class name
			single_cell,	// table cell can be defined as single
			tos = [],		// table offset
			X, Y,			// X and Y position of mouse pointer
			i;				// used in local loop
		// set previous position (current cell will not be highlighted)
		previous = function () {
			if (table_old !== null && row_old !== null && cell_old !== null) {
				table = table_old;
				row = row_old;
				cell = cell_old;
			}
		};
		// prepare X and Y position of mouse pointer
		X = pointer.x;
		Y = pointer.y;
		// find table below draggable object
		for (table = 0; table < tables.length; table++) {
			// if table is not enabled then skip table
			// by default tables don't have set redips.enabled property (undefined !== false)
			if (tables[table].redips.enabled === false) {
				continue;
			}
			// prepare table offset
			tos[0] = tables[table].redips.offset[0]; // top
			tos[1] = tables[table].redips.offset[1]; // right
			tos[2] = tables[table].redips.offset[2]; // bottom
			tos[3] = tables[table].redips.offset[3]; // left
			// if table belongs to the scrollable container then set scrollable container offset if needed
			// in case when some parts of table are hidden (for example with "overflow: auto")
			if (tables[table].sca !== undefined) {
				tos[0] = tos[0] > tables[table].sca.offset[0] ? tos[0] : tables[table].sca.offset[0]; // top
				tos[1] = tos[1] < tables[table].sca.offset[1] ? tos[1] : tables[table].sca.offset[1]; // right
				tos[2] = tos[2] < tables[table].sca.offset[2] ? tos[2] : tables[table].sca.offset[2]; // bottom
				tos[3] = tos[3] > tables[table].sca.offset[3] ? tos[3] : tables[table].sca.offset[3]; // left
			}
			// mouse pointer is inside table (or scrollable container)
			if (tos[3] < X && X < tos[1] && tos[0] < Y && Y < tos[2]) {
				// define row offsets for the selected table (row box bounds)
				row_offset = tables[table].redips.row_offset;
				// find the current row (loop skips hidden rows)
				for (row = 0; row < row_offset.length - 1; row++) {
					// if row doesn't exist (in case of hidden row) - skip it
					if (row_offset[row] === undefined) {
						continue;
					}
					// set top and bottom cell bounds
					currentCell[0] = row_offset[row][0];
					// set bottom cell bound (if is possible) - hidden row doesn't exist
					if (row_offset[row + 1] !== undefined) {
						currentCell[2] = row_offset[row + 1][0];
					}
					// hidden row (like style.display === 'none')
					else {
						// search for next visible row
						for (i = row + 2; i < row_offset.length; i++) {
							// visible row found
							if (row_offset[i] !== undefined) {
								currentCell[2] = row_offset[i][0];
								break;
							}
						}
					}
					// top bound of the next row
					if (Y <= currentCell[2]) {
						break;
					}
				}
				// remember found row
				row_found = row;
				// if loop exceeds, then set bounds for the last row (offset for the last row doesn't work in IE8, so use table bounds) 
				if (row === row_offset.length - 1) {
					currentCell[0] = row_offset[row][0];
					currentCell[2] = tables[table].redips.offset[2];
				}
				// do loop - needed for rowspaned cells (if there is any)
				do {
					// set the number of cells in the selected row
					cells = tables[table].rows[row].cells.length - 1;
					// find current cell (X mouse position between cell offset left and right)
					for (cell = cells; cell >= 0; cell--) {
						// row left offset + cell left offset
						currentCell[3] = row_offset[row][3] + tables[table].rows[row].cells[cell].offsetLeft;
						// cell right offset is left offset + cell width  
						currentCell[1] = currentCell[3] + tables[table].rows[row].cells[cell].offsetWidth;
						// is mouse pointer is between left and right offset, then cell is found
						if (currentCell[3] <= X && X <= currentCell[1]) {
							break;
						}
					}
				} // if table contains rowspaned cells and mouse pointer is inside table but cell was not found (hmm, rowspaned cell - try in upper row)
				while (tables[table].redips.rowspan && cell === -1 && row-- > 0);
				// if cell < 0 or row < 0 then use last possible location
				if (row < 0 || cell < 0) {
					previous();
				}
				// current cell found but if current row differ from previously found row (thanks too while loop with row--)
				// then test if Y is inside current cell
				// (this should prevent case where TD border > 1px and upper colspaned row like in example15)
				// logic will end in upper colspaned row while current row will not move - and that was wrong
				else if (row !== row_found) {
					// recalculate top and bottom row offset (again)
					currentCell[0] = row_offset[row][0];
					currentCell[2] = currentCell[0] + tables[table].rows[row].cells[cell].offsetHeight;
					// if Y is outside of the current row, return previous location 
					if (Y < currentCell[0] || Y > currentCell[2]) {
						previous();
					}
				}
				// set current cell (for easier access in test below)
				cell_current = tables[table].rows[row].cells[cell];
				// if current cell contain nested table(s) then set currentCell.containTable property
				// needed in handlerOnMouseMove() - see around line 1070
				if (cell_current.childNodes.length > 0 && cell_current.getElementsByTagName('table').length > 0) {
					currentCell.containTable = true;
				}
				else {
					currentCell.containTable = false;
				}
				// if current cell isn't trash cell, then search for marks in class name
				if (cell_current.className.indexOf(REDIPS.drag.trash.className) === -1) {
					// search for 'only' class name
					only_found = cell_current.className.indexOf(REDIPS.drag.only.cname) > -1 ? true : false;
					// if current cell is marked with 'only' class name
					if (only_found === true) {
						// marked cell "only" found, test for defined pairs (DIV id -> class name)
						if (cell_current.className.indexOf(only.div[obj.id]) === -1) {
							previous();
							break;
						}
					}
					// DIV objects marked with "only" can't be placed to other cells (if property "other" is "deny")
					else if (only.div[obj.id] !== undefined && only.other === 'deny') {
						previous();
						break;
					}
					else {
						// search for 'mark' class name
						mark_found = cell_current.className.indexOf(REDIPS.drag.mark.cname) > -1 ? true : false;
						// if current cell is marked and access type is 'deny' or current cell isn't marked and access type is 'allow'
						// then return previous location
						if ((mark_found === true && REDIPS.drag.mark.action === 'deny') || (mark_found === false && REDIPS.drag.mark.action === 'allow')) {
							// marked cell found, but make exception if defined pairs "DIV id -> class name" exists (return previous location)
							if (cell_current.className.indexOf(mark.exception[obj.id]) === -1) {
								previous();
								break;
							}
						}
					}
				}
				// test if current cell is defined as single
				single_cell = cell_current.className.indexOf('single') > -1 ? true : false;
				// if drag mode is "cell"
				if (mode === 'cell') {
					// if dropMode == single or current cell is single and current cell contains nodes then test if cell is occupied
					if ((REDIPS.drag.dropMode === 'single' || single_cell) && cell_current.childNodes.length > 0) {
						// if cell has only one node and that is text node then break - because this is empty cell
						if (cell_current.childNodes.length === 1 && cell_current.firstChild.nodeType === 3) {
							break;
						}
						// intialize "empty" flag to true
						empty = true;
						// open loop for each child node and jump out if 'drag' className found
						for (i = cell_current.childNodes.length - 1; i >= 0; i--) {
							if (cell_current.childNodes[i].className && cell_current.childNodes[i].className.indexOf('drag') > -1) {
								empty = false;
								break;
							} 
						}
						// if cell is not empty and old position exists ...
						if (!empty && table_old !== null && row_old !== null && cell_old !== null) {
							// .. and current position is different then source position then return previous position
							if (table_source !== table || row_source !== row || cell_source !== cell) {
								previous();
								break;
							}
						}
					}
					// current cell is marked as row handler and user is dragging DIV element over it - do not enable  
					if (cell_current.className.indexOf('rowhandler') > -1) {
						previous();
						break;
					}
					// if current row is defined as emptyRow, elements can't be dropped to these cells
					if (cell_current.parentNode.redips && cell_current.parentNode.redips.emptyRow) {
						previous();
						break;
					}
				}
				// break table loop 
				break;
			}
		}
	};


	/**
	 * Method sets background color for the current table cell and remembers previous position and background color.
	 * It is called from handlerOnMouseMove and cellChanged.
	 * @see <a href="#handlerOnMouseMove">handlerOnMouseMove</a>
	 * @see <a href="#cellChanged">cellChanged</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	setPosition = function () {
		// in case if ordinary element is placed inside 'deny' table cell
		if (table < tables.length && table !== null && row !== null && cell !== null) {
			// remember background color before setting the new background color
			bgStyleOld = getTdStyle(table, row, cell);
			// highlight current TD / TR (colors and styles are read from public property "hover"
			setTdStyle(table, row, cell);
			// remember current position (for table, row and cell)
			table_old = table;
			row_old = row;
			cell_old = cell;
		}
	};


	/**
	 * Method sets table cell(s) background styles (background colors and border styles).
	 * If tdStyle is undefined then current td/tr will be highlighted from public property hover.color_td, hover.color_tr ...
	 * @param {Integer} ti Table index.
	 * @param {Integer} ri Row index.
	 * @param {Integer} ci Cell index.
	 * @param {Object} t Object contains background color and border styles ("t" is TD style object is prepared in getTdStyle method).
	 * @see <a href="#getTdStyle">getTdStyle</a>
	 * @see <a href="#setPosition">setPosition</a>
	 * @see <a href="#cellChanged">cellChanged</a>
	 * @see <a href="#handlerOnMouseUp">handlerOnMouseUp</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	setTdStyle = function (ti, ri, ci, t) {
		// reference to the table row, loop variable and td.style
		var tr, i, s;
		// if drag mode is "cell" and threshold distance is prevailed
		if (mode === 'cell' && threshold.flag) {
			// set TD style reference
			s = tables[ti].rows[ri].cells[ci].style;
			// TD background color - tdStyle is undefined then highlight TD otherwise return previous background color
			s.backgroundColor = (t === undefined) ? REDIPS.drag.hover.colorTd : t.color[0].toString();
			// TD border - if hover.borderTd is set then take care of border style
			if (REDIPS.drag.hover.borderTd !== undefined) {
				// set border (highlight)
				if (t === undefined) {
					s.border = REDIPS.drag.hover.borderTd;
				}
				// return previous state (exit from TD)
				else {
					s.borderTopWidth = t.top[0][0];
					s.borderTopStyle = t.top[0][1];
					s.borderTopColor = t.top[0][2];
					s.borderRightWidth = t.right[0][0];
					s.borderRightStyle = t.right[0][1];
					s.borderRightColor = t.right[0][2];
					s.borderBottomWidth = t.bottom[0][0];
					s.borderBottomStyle = t.bottom[0][1];
					s.borderBottomColor = t.bottom[0][2];
					s.borderLeftWidth = t.left[0][0];
					s.borderLeftStyle = t.left[0][1];
					s.borderLeftColor = t.left[0][2];
				}
			}
		}
		// or drag mode is "row"
		else if (mode === 'row') {
			// set reference to the current table row
			tr = tables[ti].rows[ri];
			// set colors to table cells (respectively) or first color to all cells (in case of settings hover to the row)
			for (i = 0; i < tr.cells.length; i++) {
				// set reference to current TD style
				s = tr.cells[i].style;
				// TR background color - tdStyle is undefined then highlight TD otherwise return previous background color
				s.backgroundColor = (t === undefined) ? REDIPS.drag.hover.colorTr : t.color[i].toString();
				// TR border - if hover.borderTd is set then take care of border style
				if (REDIPS.drag.hover.borderTr !== undefined) {
					// set border (highlight) - source row will not have any border
					if (t === undefined) {
						// target is current table
						if (table === table_source) {
							// if row is moved above source row in current table
							if (row < row_source) {
								s.borderTop = REDIPS.drag.hover.borderTr;
							}
							// if row is moved below source row in current table 
							else {
								s.borderBottom = REDIPS.drag.hover.borderTr;
							}
						}
						// target is other table (where row will be placed is defined with public property REDIPS.drag.rowDropMode)
						else {
							// highlight top border
							if (REDIPS.drag.rowDropMode === 'before') {
								s.borderTop = REDIPS.drag.hover.borderTr;
							}
							// highlight bottom border
							else {
								s.borderBottom = REDIPS.drag.hover.borderTr;
							}
						}
					}
					// return previous state borderTop and borderBottom (exit from TD)
					else {
						s.borderTopWidth = t.top[i][0];
						s.borderTopStyle = t.top[i][1];
						s.borderTopColor = t.top[i][2];
						s.borderBottomWidth = t.bottom[i][0];
						s.borderBottomStyle = t.bottom[i][1];
						s.borderBottomColor = t.bottom[i][2];

					}
				}
			}
		}
	};


	/**
	 * Method s returns background and border styles as object for the input parameters table index, row index and cell index.
	 * @param {Integer} t Table index.
	 * @param {Integer} r Row index.
	 * @param {Integer} c Cell index.
	 * @return {Object} Object containing background color and border styles (for the row or table cell).
	 * @see <a href="#setTdStyle">setTdStyle</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	getTdStyle = function (ti, ri, ci) {
		var tr, i, c, // reference to the table row, loop variable and td reference
			// define TD style object with background color and border styles: top, right, bottom and left
			t = {color: [], top: [], right: [], bottom: [], left: []},
			// private method gets border styles: top, right, bottom, left
			border = function (c, name) {
				var width = 'border' + name + 'Width',
					style = 'border' + name + 'Style',
					color = 'border' + name + 'Color';			
				return [getStyle(c, width), getStyle(c, style), getStyle(c, color)];
			};
		// if drag mode is "cell" tdStyle.color and tdStyle.border will have only one value
		if (mode === 'cell') {
			// set TD reference
			c = tables[ti].rows[ri].cells[ci];
			// remember background color
			t.color[0] = c.style.backgroundColor;
			// remember top, right, bottom and left TD border styles if hover.borderTd property is set
			if (REDIPS.drag.hover.borderTd !== undefined) {
				t.top[0] = border(c, 'Top');
				t.right[0] = border(c, 'Right');
				t.bottom[0] = border(c, 'Bottom');
				t.left[0] = border(c, 'Left');
			}
		}
		// if drag mode is "row", then color array will contain color for each table cell
		else {
			// set reference to the current table row
			tr = tables[ti].rows[ri];
			// remember styles for each table cell
			for (i = 0; i < tr.cells.length; i++) {
				// set TD reference
				c = tr.cells[i];
				// remember background color
				t.color[i] = c.style.backgroundColor;
				// remember top and bottom TD border styles if hover.borderTr property is set
				if (REDIPS.drag.hover.borderTr !== undefined) {
					t.top[i] = border(c, 'Top');
					t.bottom[i] = border(c, 'Bottom');
				}
			}
		}
		// return TD style object
		return t;
	};


	/**
	 * Method returns array of element bounds (offset) top, right, bottom and left (needed for table grid calculation).
	 * @param {HTMLElement} box HTMLElement for box metrics.
	 * @param {String} [position] HTMLElement "position" style. Elements with style "fixed" will not have included page scroll offset.
	 * @param {Boolean} [box_scroll] If set to "false" then element scroll offset will not be included in calculation (default is "true").
	 * @return {Array} Box offset array: [ top, right, bottom, left ]
	 * @example
	 * // calculate box offset for the div id="drag"
	 * divbox = boxOffset(dragContainer);
	 * @example
	 * // include scroll position in offset
	 * offset = boxOffset(rowObj, 'fixed');
	 * @example
	 * // get DIV offset with or without "page scroll" and excluded element scroll offset
	 * cb = boxOffset(div, position, false);
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	boxOffset = function (box, position, box_scroll) {
		var scrollPosition,	// get scroll position
			oLeft = 0,		// define offset left (take care of horizontal scroll position)
			oTop  = 0,		// define offset top (take care od vertical scroll position)
			boxOld = box;	// remember box object
		// if table_position is undefined, '' or 'page_scroll' then include page scroll offset
		if (position !== 'fixed') {
			scrollPosition = getScrollPosition();	// get scroll position
			oLeft = 0 - scrollPosition[0];			// define offset left (take care of horizontal scroll position)
			oTop  = 0 - scrollPosition[1];			// define offset top (take care od vertical scroll position)
		}
		// climb up through DOM hierarchy (getScrollPosition() takes care about page scroll positions)
		if (box_scroll === undefined || box_scroll === true) {
			do {
				oLeft += box.offsetLeft - box.scrollLeft;
				oTop += box.offsetTop - box.scrollTop;
				box = box.offsetParent;
			}
			while (box && box.nodeName !== 'BODY');
		}
		// climb up to the BODY element but without scroll positions
		else {
			do {
				oLeft += box.offsetLeft;
				oTop += box.offsetTop;
				box = box.offsetParent;
			}
			while (box && box.nodeName !== 'BODY');
		}
		// return box offset array
		//        top                 right,                     bottom           left
		return [ oTop, oLeft + boxOld.offsetWidth, oTop + boxOld.offsetHeight, oLeft ];
	};

 
	/** 
	 * Method is called in every possible case when position or size of table grid could change like: page scrolling, element dropped to the table cell, element start dragging and so on.
	 * It calculates table row offsets (table grid) and saves to the "tables" array.
	 * Table rows with style display='none' are skipped.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	calculateCells = function () {
		var i, j,		// local variables used in loops
			row_offset,	// row box
			position,	// if element (table or table container) has position:fixed then "page scroll" offset should not be added
			cb;			// box offset for container box (cb)
		// open loop for each HTML table inside id=drag (table array is initialized in init() function)
		for (i = 0; i < tables.length; i++) {
			// initialize row_offset array
			row_offset = [];
			// set table style position (to exclude "page scroll" offset from calculation if needed) 
			position = getStyle(tables[i], 'position');
			// if table doesn't have style position:fixed then table container should be tested
			if (position !== 'fixed') {
				position = getStyle(tables[i].parentNode, 'position');
			}
			// backward loop has better perfomance
			for (j = tables[i].rows.length - 1; j >= 0; j--) {
				// add rows to the offset array if row is not hidden 
				if (tables[i].rows[j].style.display !== 'none') {
					row_offset[j] = boxOffset(tables[i].rows[j], position);
				}
			}
			// save table informations (table offset and row offsets)
			tables[i].redips.offset = boxOffset(tables[i], position);
			tables[i].redips.row_offset = row_offset;
		}
		// calculate box offset for the div id=drag
		divBox = boxOffset(dragContainer);
		// update scrollable container areas if needed
		for (i = 0; i < scrollData.container.length; i++) {
			// set container box style position (to exclude page scroll offset from calculation if needed) 
			position = getStyle(scrollData.container[i].div, 'position');
			// get DIV container offset with or without "page scroll" and excluded scroll position of the content
			cb = boxOffset(scrollData.container[i].div, position, false);
			// prepare scrollable container areas
			scrollData.container[i].offset = cb;
			scrollData.container[i].midstX = (cb[1] + cb[3]) / 2;
			scrollData.container[i].midstY = (cb[0] + cb[2]) / 2;
		}
	};


	/**
	 * Method returns current page scroll position as array.
	 * @return {Array} Returns array with two members [ scrollX, scrollY ].
	 * @public
	 * @function
	 * @name REDIPS.drag#getScrollPosition
	 */
	getScrollPosition = function () {
		// define local scroll position variables
		var scrollX, scrollY;
		// Netscape compliant
		if (typeof(window.pageYOffset) === 'number') {
			scrollX = window.pageXOffset;
			scrollY = window.pageYOffset;
		}
		// DOM compliant
		else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
			scrollX = document.body.scrollLeft;
			scrollY = document.body.scrollTop;
		}
		// IE6 standards compliant mode
		else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
			scrollX = document.documentElement.scrollLeft;
			scrollY = document.documentElement.scrollTop;
		}
		// needed for IE6 (when vertical scroll bar was on the top)
		else {
			scrollX = scrollY = 0;
		}
		// return scroll positions
		return [ scrollX, scrollY ];
	};


	/**
	 * Horizontal auto scroll method.
	 * @param {HTMLElement} so Window or DIV element (so - scroll object).
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	autoScrollX = function (so) {
		var pos,			// left style position
			old,			// old window scroll position (needed for window scrolling)
			scrollPosition,	// define current scroll position
			maxsp,			// maximum scroll position
			edgeCrossed,	// crossed edge for window and scrollable container
			X = pointer.x,	// define pointer X position
			Y = pointer.y;	// define pointer Y position
		// if mouseup then stop handling "current cell"
		if (edge.flag.x > 0) {
			// calculate cell (autoscroll is working)
			calculateCells();
			// set current table row and table cell
			setTableRowColumn();
			// set current table, row and cell if mouse pointer is inside DIV id="drag"
			if (X < divBox[1] && X > divBox[3] && Y < divBox[2] && Y > divBox[0]) {
				cellChanged();
			}
		}
		// save scroll object to the global variable for the first call from handlerOnMouseMove
		// recursive calls will not enter this code and reference to the scrollData.obj will be preserved
		if (typeof(so) === 'object') {
			scrollData.obj = so;
		}
		// window autoscroll (define current, old and maximum scroll position)
		if (scrollData.obj === window) {
			scrollPosition = old = getScrollPosition()[0];
			maxsp = scrollData.width - screen.width;
			edgeCrossed = edge.page.x;
		}
		// scrollable container (define current and maximum scroll position)
		else {
			scrollPosition = scrollData.obj.scrollLeft;
			maxsp = scrollData.obj.scrollWidth - scrollData.obj.clientWidth;
			edgeCrossed = edge.div.x;
		}
		// if scrolling is possible
		if (edge.flag.x > 0 && ((edgeCrossed < 0 && scrollPosition > 0) || (edgeCrossed > 0 && scrollPosition < maxsp))) {
			// if object is window
			if (scrollData.obj === window) {
				// scroll window
				window.scrollBy(edgeCrossed, 0);
				// get new window scroll position (after scrolling)
				// because at page top or bottom edgeY can be bigger then the rest of scrolling area
				// it will be nice to know how much was window scrolled after scrollBy command 
				scrollPosition = getScrollPosition()[0];
				// get current object top style
				pos = parseInt(obj.style.left, 10);
				if (isNaN(pos)) {
					pos = 0;
				}
			}
			// or scrollable container
			else {
				scrollData.obj.scrollLeft += edgeCrossed;
			}
			// recursive autoscroll call 
			setTimeout(autoScrollX, REDIPS.drag.scroll.speed);
		}
		// autoscroll is ended: element is out of the page edge or maximum position is reached (left or right)
		else {
			// return onscroll event handler (to window or div element)
			REDIPS.event.add(scrollData.obj, 'scroll', calculateCells);
			// reset auto scroll flag X
			edge.flag.x = 0;
			// reset current cell position
			currentCell = [0, 0, 0, 0];
		}
	};


	/**
	 * Vertical auto scroll method.
	 * @param {HTMLElement} so Window or DIV element (so - scroll object).
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	autoScrollY = function (so) {
		var pos,			// top style position
			old,			// old window scroll position (needed for window scrolling)
			scrollPosition,	// define current scroll position
			maxsp,			// maximum scroll position
			edgeCrossed,	// crossed edge for window and scrollable container
			X = pointer.x,	// define pointer X position
			Y = pointer.y;	// define pointer Y position
		// if mouseup then stop handling "current cell"
		if (edge.flag.y > 0) {
			// calculate cell (autoscroll is working)
			calculateCells();
			// set current table row and table cell
			setTableRowColumn();
			// set current table, row and cell if mouse pointer is inside DIV id="drag"
			if (X < divBox[1] && X > divBox[3] && Y < divBox[2] && Y > divBox[0]) {
				cellChanged();
			}
		}
		// save scroll object to the global variable for the first call from handlerOnMouseMove
		// recursive calls will not enter this code and reference to the scrollData.obj will be preserved
		if (typeof(so) === 'object') {
			scrollData.obj = so;
		}
		// window autoscroll (define current, old and maximum scroll position)
		if (scrollData.obj === window) {
			scrollPosition = old = getScrollPosition()[1];
			maxsp = scrollData.height - screen.height;
			edgeCrossed = edge.page.y;
		}
		// scrollable container (define current and maximum scroll position)
		else {
			scrollPosition = scrollData.obj.scrollTop;
			maxsp = scrollData.obj.scrollHeight - scrollData.obj.clientHeight;
			edgeCrossed = edge.div.y;
		}
		// if scrolling is possible
		if (edge.flag.y > 0 && ((edgeCrossed < 0 && scrollPosition > 0) || (edgeCrossed > 0 && scrollPosition < maxsp))) {
			// if object is window
			if (scrollData.obj === window) {
				// scroll window
				window.scrollBy(0, edgeCrossed);
				// get new window scroll position (after scrolling)
				// because at page top or bottom edgeY can be bigger then the rest of scrolling area
				// it will be nice to know how much was window scrolled after scrollBy command 
				scrollPosition = getScrollPosition()[1];
				// get current object top style
				pos = parseInt(obj.style.top, 10);
				if (isNaN(pos)) {
					pos = 0;
				}
			}
			// or scrollable container
			else {
				scrollData.obj.scrollTop += edgeCrossed;
			}
			// recursive autoscroll call 
			setTimeout(autoScrollY, REDIPS.drag.scroll.speed);
		}
		// autoscroll is ended: element is out of the page edge or maximum position is reached (top or bottom)
		else {
			// return onscroll event handler (to window or div element)
			REDIPS.event.add(scrollData.obj, 'scroll', calculateCells);
			// reset auto scroll flag Y
			edge.flag.y = 0;
			// reset current cell position
			currentCell = [0, 0, 0, 0];
		}
	};
	

	/**
	 * Method clones DIV element and returns cloned element reference.
	 * "clone" class name will not be copied in cloned element (in case if source element contains "clone" class name).
	 * This method is called internally when DIV elements are cloned.
	 * @param {HTMLElement} div DIV element to clone.
	 * @param {Boolean} [drag] If set to true, then cloned DIV element will be ready for dragging (otherwise element will be only cloned).
	 * @return {HTMLElement} Returns cloned DIV element.
	 * @public
	 * @function
	 * @name REDIPS.drag#cloneObject
	 */
	cloneObject = function (div, drag) {
		var divCloned = div.cloneNode(true),	// cloned DIV element
			cname = divCloned.className,		// set class names of cloned DIV element
			offset,								// offset of the original object
			offsetDragged;						// offset of the new object (cloned)
		// if cloned DIV element should be ready for dragging
		if (drag === true) {
			// append cloned element to the DIV id="redips_clone"
			document.getElementById('redips_clone').appendChild(divCloned);
			// set high z-index
			divCloned.style.zIndex = 999;
			// set style to fixed to allow dragging DIV object
			divCloned.style.position = 'fixed';
			// set offset for original and cloned element
			offset = boxOffset(div);
			offsetDragged = boxOffset(divCloned);
			// calculate top and left offset of the new object
			divCloned.style.top   = (offset[0] - offsetDragged[0]) + 'px';
			divCloned.style.left  = (offset[3] - offsetDragged[3]) + 'px';
		}
		// get IE (all versions) to allow dragging outside the window (?!)
		// this was needed here also -  despite setCaputure in onmousedown
		if (divCloned.setCapture) {
			divCloned.setCapture();
		}
		// remove "clone" and "climitX_Y" class names
		cname = cname.replace('clone', '');
		cname = cname.replace(/climit(\d)_(\d+)/, '');
		// set class names with normalized spaces to the cloned DIV element
		divCloned.className = normalize(cname);
		// if counter is undefined, set 0
		if (clonedId[div.id] === undefined) {
			clonedId[div.id] = 0;
		}
		// set id for cloned element (append id of "clone" element - tracking the origin)
		// id is separated with "c" ("_" is already used to compound id, table, row and column)  
		divCloned.id = div.id + 'c' + clonedId[div.id];
		// increment clonedId for cloned element
		clonedId[div.id] += 1;
		// copy custom properties to the DIV element and child DIV elements and register event handlers
		copyProperties(div, divCloned);
		// return reference to the cloned DIV element	
		return (divCloned);
	};


	/**
	 * Method copies custom properties from source element to the cloned element and sets event handlers (onmousedown and ondblclick).
	 * This action will be taken on DIV element itself and all child DIV elements.
	 * Needed in case when DIV element is cloned or ROW is cloned (for dragging mode="row").
	 * @param {HTMLElement} src Source element (DIV or TR element).
	 * @param {HTMLElement} cln Cloned element (DIV or TR element).
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	copyProperties = function (src, cln) {
		var	copy = [],	// copy method
			childs;		// copy properties for child elements (this method calls "copy" method)
		// define copy method for DIV elements (e1 source element, e2 cloned element)
		// http://stackoverflow.com/questions/4094811/javascript-clonenode-and-properties
		copy[0] = function (e1, e2) {
			// if redips property exists in source element
			if (e1.redips) {
				// copy custom properties (redips.enabled,  redips.container ...)
				e2.redips = {};
				e2.redips.enabled = e1.redips.enabled;
				e2.redips.container = e1.redips.container;
				// set onmousedown, ontouchstart and ondblclick event handler if source element is enabled
				if (e1.redips.enabled) {
					registerEvents(e2);
				}
			}
		};
		// define copy method for TR elements
		copy[1] = function (e1, e2) {
			// if redips property exists in source element
			if (e1.redips) {
				// copy custom properties (redips.emptyRow ...)
				e2.redips = {};
				e2.redips.emptyRow = e1.redips.emptyRow;
			}
		};
		// define method to copy properties for child elements (input parameter is element index 0 - DIV, 1 - TR)
		childs = function (e) {
			var	el1, el2,			// collection of DIV/TR elements in source and cloned element
				i,					// loop variable
				tn = ['DIV', 'TR'];	// tag name
			// collect child DIV/TR elements from the source element (possible if div element contains table)
			el1 = src.getElementsByTagName(tn[e]);
			// collect child DIV/TR elements from cloned element
			el2 = cln.getElementsByTagName(tn[e]);
			// copy custom properties (redips.enabled,  redips.container ...) and set event handlers to child DIV elements
			for (i = 0; i < el2.length; i++) {
				copy[e](el1[i], el2[i]);
			}
		};
		// if source element is DIV element then copy custom properties for DIV element
		if (src.nodeName === 'DIV') {
			copy[0](src, cln);
		}
		// if source element is TR element then copy custom properties for TR element
		else if (src.nodeName === 'TR') {
			copy[1](src, cln);
		}
		// copy properties for DIV child elements
		childs(0);
		// copy properties for TR child elements
		childs(1);
	};


	/**
	 * Method updates climit1_X or climit2_X class name (X defines cloning limit).
	 * <ul>
	 * <li>climit1_X - after cloning X elements, last element will be normal drag-able element</li>
	 * <li>climit2_X - after cloning X elements, last element will stay unmovable</li>
	 * </ul>
	 * @param {HTMLElement} el Element on which cname class should be updated.
	 * @param {Integer} value Increment or decrement climit value.
	 * @see <a href="#handlerOnMouseUp">handlerOnMouseUp</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	cloneLimit = function (el, value) {
		// declare local variables 
		var matchArray,	// match array
			limitType,	// limit type (1 - clone becomes "normal" drag element at last; 2 - clone element stays immovable)
			limit,		// limit number
			classes;	// class names of clone element
		// read class name from element
		classes = el.className;
		// match climit class name		
		matchArray = classes.match(/climit(\d)_(\d+)/);
		// if DIV class contains climit
		if (matchArray !== null) {
			// prepare limitType (1 or 2) and limit
			limitType = parseInt(matchArray[1], 10); 
			limit = parseInt(matchArray[2], 10);
			// if current limit is 0 and should be set to 1 then return "cloning" to the DIV element
			if (limit === 0 && value === 1) {
				// add "clone" class to class attribute
				classes += ' clone';
				// enable DIV element for climit2 type
				if (limitType === 2) {
					enableDrag(true, el);
				}
			}
			// update limit value
			limit += value;
			// update climit class name with new limit value
			classes = classes.replace(/climit\d_\d+/g, 'climit' + limitType + '_' + limit);
			// test if limit drops to zero
			if (limit <= 0) {
				// no more cloning, cut out "clone" from class name
				classes = classes.replace('clone', '');
				// if limit type is 2 then disable clone element (it will stay in cell)
				if (limitType === 2) {
					// disable source DIV element
					enableDrag(false, el);
					// call event.clonedEnd2 handler
					REDIPS.drag.event.clonedEnd2(); 
				}
				else {
					// call event.clonedEnd1 handler
					REDIPS.drag.event.clonedEnd1();
				}
			}
			// normalize spaces and return classes to the clone object 
			el.className = normalize(classes);
		}
	};


	/**
	 * Method returns true or false if element needs to have control.
	 * Elements like A, INPUT, SELECT, OPTION, TEXTAREA should have its own control (method returns "true").
	 * If element contains "nodrag" class name then dragging will be skipped (see example11 "Drag handle on titlebar").
	 * <ul>
	 * <li>true - click on element will not start dragging (element has its own control)</li>
	 * <li>false - click on element will start dragging</li>
	 * </ul>
	 * @param {Event} evt Event information.
	 * @return {Boolean} Returns true or false if element needs to have control.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	elementControl = function (evt) {
		// declare elementControl flag, source tag name and element classes
		var flag = false,
			srcName,
			classes,						// class names of DIV element;
			regexNodrag = /\bnodrag\b/i;	// regular expression to search "nodrag" class name 
		// set source tag name and classes for IE and FF
		if (evt.srcElement) {
			srcName = evt.srcElement.nodeName;
			classes = evt.srcElement.className;
		}
		else {
			srcName = evt.target.nodeName;
			classes = evt.target.className;
		}
		// set flag (true or false) for clicked elements
		switch (srcName) {
		case 'A':
		case 'INPUT':
		case 'SELECT':
		case 'OPTION':
		case 'TEXTAREA':
			flag = true;
			break;
		// none of form elements
		default:
			// if element has "nodrag" class name then dragging will be skipped 
			if (regexNodrag.test(classes)) {
				flag = true;
			}
			else {
				flag = false;
			}
		}
		// return true/false flag
		return flag;
	};


	/**
	 * Method attaches / detaches onmousedown, ontouchstart and ondblclick events to DIV elements and attaches onscroll event to the scroll containers in initialization phase.
	 * It also can be used for element initialization after DIV element was manually added to the table.
	 * If class attribute of DIV container contains "noautoscroll" class name then autoScroll option will be disabled.
	 * @param {Boolean|String} enableFlag Enable / disable element (or element subtree like table, dragging container ...).
	 * @param {HTMLElement|String} [el] HTML node or CSS selector to enable / disable. Parameter defines element reference or CSS selector of DIV elements to enable / disable.
	 * @example
	 * // enable element with id="id123"
	 * rd.enableDrag(true, '#id123');
	 *  
	 * // or init manually added element with known id
	 * REDIPS.drag.enableDrag(true, '#id234');
	 *  
	 * // disable all DIV elements in drag1 subtree 
	 * rd.enableDrag(false, '#drag1 div')
	 *  
	 * // init DIV elements in dragging area (including newly added DIV element)
	 * REDIPS.drag.enableDrag('init');
	 *  
	 * // init added element with reference myElement
	 * REDIPS.drag.enableDrag(true, myElement);
	 *  
	 * // disable all DIV elements within TD (td is reference to TD node)
	 * REDIPS.drag.enableDrag(false, td);
	 * @public
	 * @function
	 * @name REDIPS.drag#enableDrag
	 * @see <a href="#enableTable">enableTable</a>
	 */
	enableDrag = function (enable_flag, el) {
		// define local variables
		var i, j, k,		// local variables used in loop
			div = [],		// collection of div elements contained in tables or one div element
			tbls = [],		// collection of tables inside scrollable container
			borderStyle,	// border style (solid or dotted)
			opacity,		// (integer) set opacity for enabled / disabled elements
			cursor,			// cursor style (move or auto)
			overflow,		// css value of overflow property
			autoscroll,		// boolean - if scrollable container will have autoscroll option (default is true)
			enabled,		// enabled property (true or false) 
			cb,				// box offset for container box (cb)
			position,		// if table container has position:fixed then "page scroll" offset should not be added
			regexDrag = /\bdrag\b/i,	// regular expression to search "drag" class name
			regexNoAutoscroll = /\bnoautoscroll\b/i;	// regular expression to search "noautoscroll" class name
		// set opacity for disabled elements from public property "opacityDisabled" 
		opacity = REDIPS.drag.style.opacityDisabled;
		// set styles for enabled DIV element
		if (enable_flag === true || enable_flag === 'init') {
			borderStyle = REDIPS.drag.style.borderEnabled;
			cursor = 'move';
			enabled = true;
		}
		// else set styles for disabled DIV element
		else {
			borderStyle = REDIPS.drag.style.borderDisabled;
			cursor = 'auto';
			enabled = false;
		}
		// collect DIV elements inside current drag area (drag elements and scroll containers)
		// e.g. enableDrag(true)
		if (el === undefined) {
			div = dragContainer.getElementsByTagName('div');
		}
		// "el" is string (CSS selector) - it can collect one DIV element (like "#d12") or many DIV elements (like "#drag1 div")
		else if (typeof(el) === 'string') {
			div = document.querySelectorAll(el);
		}
		// "el" is node reference to element that is not DIV class="drag"
		else if (typeof(el) === 'object' && (el.nodeName !== 'DIV' || el.className.indexOf('drag') === -1)) {
			div = el.getElementsByTagName('div');
		}
		// none of above, el is DIV class="drag", so prepare array with one DIV element
		else {
			div[0] = el;
		}
		// 
		// main loop that goes through all DIV elements
		//
		for (i = 0, j = 0; i < div.length; i++) {
			// if DIV element contains "drag" class name
			if (regexDrag.test(div[i].className)) {
				// add reference to the DIV container (initialization or newly added element to the table)
				// this property should not be changed in later element enable/disable
				if (enable_flag === 'init' || div[i].redips === undefined) {
					// create a "property object" in which all custom properties will be saved
					div[i].redips = {};
					div[i].redips.container = dragContainer;
				}
				// remove opacity mask
				else if (enable_flag === true && typeof(opacity) === 'number') {
					div[i].style.opacity = '';
					div[i].style.filter = '';						
				}
				// set opacity for disabled elements
				else if (enable_flag === false && typeof(opacity) === 'number') {
					div[i].style.opacity = opacity / 100;
					div[i].style.filter = 'alpha(opacity=' + opacity + ')';					
				}
				// register event listener for DIV element
				registerEvents(div[i], enabled);
				// set styles for DIV element
				div[i].style.borderStyle = borderStyle;
				div[i].style.cursor = cursor;
				// add enabled property to the DIV element (true or false)
				div[i].redips.enabled = enabled;
			}
			// attach onscroll event to the DIV element in init phase only if DIV element has overflow other than default value 'visible'
			// and that means scrollable DIV container
			else if (enable_flag === 'init') {
				// ask for overflow style
				overflow = getStyle(div[i], 'overflow');
				// if DIV is scrollable
				if (overflow !== 'visible') {
					// define onscroll event handler for scrollable container
					REDIPS.event.add(div[i], 'scroll', calculateCells);
					// set container box style position (to exclude page scroll offset from calculation if needed) 
					position = getStyle(div[i], 'position');
					// get DIV container offset with or without "page scroll" and excluded scroll position of the content
					cb = boxOffset(div[i], position, false);
					// search for noautoscroll option
					if (regexNoAutoscroll.test(div[i].className)) {
						autoscroll = false;
					}
					else {
						autoscroll = true;
					}
					// prepare scrollable container areas
					scrollData.container[j] = {
						div : div[i],					// reference to the scrollable container
						offset : cb,					// box offset of the scrollable container
						midstX : (cb[1] + cb[3]) / 2,	// middle X
						midstY : (cb[0] + cb[2]) / 2,	// middle Y
						autoscroll : autoscroll			// autoscroll enabled or disabled (true or false)
					};
					// search for tables inside scrollable container
					tbls = div[i].getElementsByTagName('table');
					// loop goes through found tables inside scrollable area 
					for (k = 0; k < tbls.length; k++) {
						// add a reference to the corresponding scrollable area
						tbls[k].sca = scrollData.container[j];
					}
					// increase scrollable container counter
					j++;
				}
			}
		}
	};


	/**
	 * Method deletes DIV element from table.
	 * Input parameter is DIV reference or id of DIV element.
	 * @param {String|HTMLElement} el Id of DIV element or reference of DIV element that should be deleted. 
	 * @example
	 * // delete DIV element in event.dropped() event handler
	 * rd.event.dropped = function () {
	 *     rd.deleteObject(rd.obj);
	 * }
	 *  
	 * // delete DIV element with id="d1"
	 * rd.deleteObject('d1'); 
	 * @public
	 * @function
	 * @name REDIPS.drag#deleteObject
	 */
	deleteObject = function (el) {
		var div, i;
		// if "el" is DIV reference then remove DIV element
		if (typeof(el) === 'object' && el.nodeName === 'DIV') {
			el.parentNode.removeChild(el);
		}
		// else try to delete DIV element with its ID
		else if (typeof(el) === 'string') {
			// search for DIV element inside current drag area (drag elements and scrollable containers)
			div = document.getElementById(el);
			// if div element exists then it will be deleted
			if (div) {
				div.parentNode.removeChild(div);
			}
		}
	};


	/**
	 * This method can select tables by class name and mark them as enabled / disabled.
	 * Instead of class name, it it possible to send table reference for enable / disable.
	 * By default, all tables are enabled to accept dropped elements.
	 * @param {Boolean} enable_flag Enable / disable one or more tables.
	 * @param {String|HTMLElement} el Class name of table(s) to enable/disable or table reference to enable/disable. 
	 * @example
	 * // disable tables with class name 'mini'
	 * enableTable(false, 'mini');
	 * @public
	 * @function
	 * @name REDIPS.drag#enableTable
	 * @see <a href="#enableDrag">enableDrag</a>
	 */
	enableTable = function (enable_flag, el) {
		var i;
		// if "el" is table reference then set enable/disable to the table
		if (typeof(el) === 'object' && el.nodeName === 'TABLE') {
			el.redips.enabled = enable_flag;
		}
		// else "el" is table class name
		else {
			// loop through tables array
			for (i = 0; i < tables.length; i++) {
				// if class name is found then set redips.enabled property to the table (redips_enabled is tested inside setTableRowColumn() method)
				if (tables[i].className.indexOf(el) > -1) {
					tables[i].redips.enabled = enable_flag;
				}
			}
		}
	};


	/**
	 * Method returns style value for requested HTML element and style name.
	 * @param {HTMLElement} el Requested HTML element.
	 * @param {String} style_name Asked style name.
	 * @return {String} Returns style value.
	 * @see <a href="http://www.quirksmode.org/dom/getstyles.html">http://www.quirksmode.org/dom/getstyles.html</a>
	 * @public
	 * @function
	 * @name REDIPS.drag#getStyle
	 */
	getStyle = function (el, style_name) {
		var val; // value of requested object and property
		if (el && el.currentStyle) {
			val = el.currentStyle[style_name];
		}
		else if (el && window.getComputedStyle) {
//			val = document.defaultView.getComputedStyle(el, null).getPropertyValue(style_name);
			val = document.defaultView.getComputedStyle(el, null)[style_name];  
		}
		return val;
	};


	/**
	 * Method returns a reference of the required parent element.
	 * @param {String} tag_name Tag name of parent element.
	 * @param {HTMLElement} el Start position to search.
	 * @param {Integer} [skip] How many found nodes should be skipped. For example when start node is TD in inner table and findParent() should return reference of the outside table.
	 * @example
	 * // find parent TABLE element (from cell reference)
	 * tbl = findParent('TABLE', cell);
	 *  
	 * // find reference of the outside table (start node is TD in inner table - first TABLE node should be skipped)
	 * tbl = findParent('TABLE', cell, 1);
	 * @return {HTMLElement} Returns reference of the found parent element.
	 * @public
	 * @function
	 * @name REDIPS.drag#findParent
	 */
	findParent = function (tag_name, el, skip) {
		// move "el" one level up (to prevent finding node itself)
		el = el.parentNode;
		// if skip is not defined then set it to 0
		if (skip === undefined) {
			skip = 0;
		}
		// loop up until parent element is found 
		while (el && el.nodeName !== tag_name) {
			el = el.parentNode;
			// if node is found and needs to be skipped then decrease skip counter and move pointer to the parent node again
			if (el && el.nodeName === tag_name && skip > 0) {
				skip--;
				el = el.parentNode;
			}
	    }
	    // return found element
	    return el;
	};


	/**
	 * Method returns data (cell reference, row index and column index) for first or last cell in table or row / column.
	 * @param {String} param Parameter defines first or last table cell (values are "first", "firstInColumn", "firstInRow", "last", "lastInColumn", "lastInRow").
	 * @param {HTMLElement} el Table cell reference (td). For "first" or "last" request, el can be any HTMLElement within table.
	 * @example
	 * // find first cell in row (el is table cell reference)
	 * firstInRow = findCell('firstInRow', el);
	 * 
	 * // find last cell in table (el is reference of any cell inside table)
	 * last = findCell('last', el);
	 * 
	 * // find last cell in column (el is table cell reference)
	 * lastInColumn = findCell('lastInColumn', el);
	 * @return {Array} Returns array with row index, column index and cell reference, 
	 * @public
	 * @function
	 * @name REDIPS.drag#findCell
	 */
	findCell = function (param, el) {
		// find parent table (if "el" is already table then "el" reference will not change)
		var tbl = findParent('TABLE', el),
			ri,	// row index
			ci,	// cell index
			c;	// cell reference
		switch (param) {
		// first in column
		case 'firstInColumn':
			ri = 0;
			ci = el.cellIndex;
			break;
		// first in row
		case 'firstInRow':
			ri = el.parentNode.rowIndex;
			ci = 0;
			break;
		// last in column
		case 'lastInColumn':
			ri = tbl.rows.length - 1;
			ci = el.cellIndex;
			break;
		// last in row (cell index for current row)
		case 'lastInRow':
			ri = el.parentNode.rowIndex;
			ci = tbl.rows[ri].cells.length - 1;
			break;
		// last in table (cell index for last row)
		case 'last':
			ri = tbl.rows.length - 1;
			ci = tbl.rows[ri].cells.length - 1;
			break;
		// define cell reference for first table cell (row and column indexes are 0) 
		default:
			ri = ci = 0;
		}
		// set table cell reference
		c = tbl.rows[ri].cells[ci];
	    // return cell data as array: row index, cell index and td reference
	    return [ri, ci, c];
	};



	/**
	 * Method scans table content and prepares query string or JSON format for submitting to the server.
	 * Input parameters are id / table reference and optional output format.
	 * @param {String|HTMLElement} tbl Id or reference of table that will be scanned.
	 * @param {String} [type] Type defines output format. If set to "json" then output will be JSON format otherwise output will be query string.
	 * @return {String} Returns table content as query string or in JSON format.
	 * @example
	 * Query string:
	 * 'p[]='+id+'_'+r+'_'+c+'&p[]='+id+'_'+r+'_'+c + ...
	 *  
	 * JSON:
	 * [["id",r,c],["id",r,c],...]
	 *  
	 * id - element id
	 * r  - row index
	 * c  - cell index
	 *  
	 * Query string example:
	 * p[]=d1_1_0&p[]=d2_1_1&p[]=d3_5_2&p[]=d4_5_3
	 *  
	 * JSON example:
	 * [["d1",1,0],["d2",1,1],["d3",5,2],["d4",5,3]]
	 * @see <a href="#saveParamName">saveParamName</a>
	 * @public
	 * @function
	 * @name REDIPS.drag#saveContent
	 */
	saveContent = function (tbl, type) {
		var query = '',						// define query parameter
			tbl_start,						// table loop starts from tbl_start parameter
			tbl_end,						// table loop ends on tbl_end parameter
			tbl_rows,						// number of table rows
			cells,							// number of cells in the current row
			tbl_cell,						// reference to the table cell
			cn,								// reference to the child node
			id, r, c, d,					// variables used in for loops
			JSONobj = [],					// prepare JSON object
			pname = REDIPS.drag.saveParamName;	// set parameter name (default is 'p')
		// if input parameter is string, then set reference to the table
		if (typeof(tbl) === 'string') {
			tbl = document.getElementById(tbl);
		}
		// tbl should be reference to the TABLE object
		if (tbl !== undefined && typeof(tbl) === 'object' && tbl.nodeName === 'TABLE') {
			// define number of table rows
			tbl_rows = tbl.rows.length;
			// iterate through each table row
			for (r = 0; r < tbl_rows; r++) {
				// set the number of cells in the current row
				cells = tbl.rows[r].cells.length;
				// iterate through each table cell
				for (c = 0; c < cells; c++) {
					// set reference to the table cell
					tbl_cell = tbl.rows[r].cells[c];
					// if cells isn't empty (no matter is it allowed or denied table cell) 
					if (tbl_cell.childNodes.length > 0) {
						// cell can contain many DIV elements
						for (d = 0; d < tbl_cell.childNodes.length; d++) {
							// set reference to the child node
							cn = tbl_cell.childNodes[d];
							// childNode should be DIV with containing "drag" class name
							if (cn.nodeName === 'DIV' && cn.className.indexOf('drag') > -1) { // and yes, it should be uppercase
								// prepare query string
								query += pname + '[]=' + cn.id + '_' + r + '_' + c + '&';
								// push values for DIV element as Array to the Array
								JSONobj.push([cn.id, r, c]);
							}
						}
					}
				}
			}
			// prepare query string in JSON format (only if array isn't empty)
			if (type === 'json' && JSONobj.length > 0) {
				query = JSON.stringify(JSONobj);
			}
			else {
				// cut last '&' from query string
				query = query.substring(0, query.length - 1);
			}
		}
		// return prepared parameters (if tables are empty, returned value could be empty too) 
		return query;
	};


	/**
	 * Method relocates DIV elements from source table cell to the target table cell (with optional animation).
	 * If animation is enabled, then target table will be disabled until animated element reaches destination cell.
	 * In animation mode, event.relocated() will be called after animation is finished.
	 * @param {HTMLElement} from Source table cell.
	 * @param {HTMLElement} to Target table cell.
	 * @param {String} [mode] Relocation mode "instant" or "animation". Default is "instant".
	 * @public
	 * @function
	 * @see <a href="#event:relocateBefore">event.relocateBefore</a>
	 * @see <a href="#event:relocateAfter">event.relocateAfter</a>
	 * @see <a href="#event:relocateEnd">event.relocateEnd</a>
	 * @name REDIPS.drag#relocate
	 */
	relocate = function (from, to, mode) {
		var i, j,	// loop variables
			tbl2,	// target table
			idx2,	// target table index
			cn,		// number of child nodes
			div,	// DIV element (needed in for loop)
			move;	// move object (private function)
		// define private move function (after animation is finished table will be enabled)
		move = function (el, to) {
			// call relocateBefore event handler for this element
			REDIPS.drag.event.relocateBefore(el, to);
			// define target position
			var target = REDIPS.drag.getPosition(to);
			// move object
			REDIPS.drag.moveObject({
				obj: el,
				target: target,
				callback: function (div) {
					// set reference to the table and table index
					var tbl = REDIPS.drag.findParent('TABLE', div),
						idx = tbl.redips.idx;
					// call relocateAfter event handler for this div element
					REDIPS.drag.event.relocateAfter(div, to);
					// decrease animation counter per table
					animationCounter[idx]--;
					// after last element is placed the table then table should be enabled
					if (animationCounter[idx] === 0) {
						// call event handler after relocation is finished
						REDIPS.drag.event.relocateEnd();
						// enable target table
						REDIPS.drag.enableTable(true, tbl);
					}
				}
			});
		};
		// test if "from" cell is equal to "to" cell then do nothing
		if (from === to) {
			return;
		}
		// "from" and "to" should be element nodes, if not then return from method
		if (typeof(from) !== 'object' || typeof(to) !== 'object') {
			return;
		}
		// define childnodes length before loop
		cn = from.childNodes.length;
		// if mode is "animation"
		if (mode === 'animation') {
			// if child nodes exist
			if (cn > 0) {
				// define target table reference and target table index
				tbl2 = findParent('TABLE', to);
				idx2 = tbl2.redips.idx;
				// disable target table
				REDIPS.drag.enableTable(false, tbl2);
				// loop through all child nodes in table cell
				for (i = 0; i < cn; i++) {
					// relocate (with animation) only DIV elements
					if (from.childNodes[i].nodeType === 1 && from.childNodes[i].nodeName === 'DIV') {
						// increase animated counter (counter is initially set to 0)
						animationCounter[idx2]++;
						// move DIV element to the target cell
						move(from.childNodes[i], to);
					}
				}
			}
		}
		// instant mode
		else {
			// loop through all child nodes in table cell
			// 'j', not 'i' because NodeList objects in the DOM are live
			for (i = 0, j = 0; i < cn; i++) {
				// relocate only DIV elements
				if (from.childNodes[j].nodeType === 1 && from.childNodes[j].nodeName === 'DIV') {
					// set DIV element
					div = from.childNodes[j];
					// call relocateBefore event handler for this element
					REDIPS.drag.event.relocateBefore(div, to);
					// append DIV element to the table cell
					to.appendChild(div);
					// register event listeners (FIX for Safari Mobile) if DIV element is not disabled
					if (div.redips && div.redips.enabled !== false) {
						registerEvents(div);
					}
					// call relocateAfter event handler
					REDIPS.drag.event.relocateAfter(div);
				}
				// skip text nodes, attribute nodes ...
				else {
					j++;
				}
			}	
		}
	};


	/**
	 * Method tests TD if is empty or removes elements from table cell.
	 * Cell is considered as empty if does not contain any child nodes or if cell has only one text node.
	 * In other words, if cell contains only text then it will be treated as empty cell.
	 * @param {HTMLElement} td Table cell to test or from which all the elements will be deleted.
	 * @param {String} [mode] If mode is set to "test" then method will only test TD and return true or false.
	 * @example
	 * // set REDIPS.drag reference
	 * var rd = REDIPS.drag;
	 * // search for TABLE element (from cell reference)
	 * tbl = rd.emptyCell(td);
	 *  
	 * // how to test TD if cell is occupied
	 * var empty = rd.emptyCell(td, 'test');  
	 * @return {Boolean|Array} Returns true/false depending on cell content or array with deleted child nodes.
	 * @public
	 * @function
	 * @name REDIPS.drag#emptyCell
	 */
	emptyCell = function (tdElement, mode) {
		var cn,			// number of child nodes
			el = [],	// removed elements will be saved in array
			flag,		// empty cell flag
			i;			// loop variable
		// td should be table cell element
		if (tdElement.nodeName !== 'TD') {
			return;
		}
		// define childnodes length before loop (not in loop because NodeList objects in the DOM are live)
		cn = tdElement.childNodes.length;
		// if mode is set to "test" then check for cell content
		if (mode === 'test') {
			// in case of source cell, return undefined
			if (td.source === tdElement) {
				flag = undefined;
			}
			// cell without child nodes or if cell has only one node and that is text node then cell is empty
			else if (tdElement.childNodes.length === 0 || (tdElement.childNodes.length === 1 && tdElement.firstChild.nodeType === 3)) {
				flag = true;
			}
			// otherwise, cell contain some elements
			else {
				flag = false;
			}
			// return empty flag state
			return flag;
		}
		// otherwise delete all child nodes from td
		else {
			for (i = 0; i < cn; i++) {
				// save node reference
				el.push(tdElement.childNodes[0]);
				// delete node
				tdElement.removeChild(tdElement.childNodes[0]);
			}
			// return array with references od deleted nodes
			return el;
		}
	};


	/**
	 * Method shifts table content horizontally or vertically. REDIPS.drag.shift.mode defines the way of how content will be shifted.
	 * Useful for sorting table content in any direction.
	 * @param {HTMLElement} td1 Source table cell.
	 * @param {HTMLElement} td2 Target table cell.
	 * @example
	 * // define first and last table cell
	 * var firstCell = document.getElementById('firstCellOnTable'),
	 *     lastCell = document.getElementById('lastCellOnTable');
	 * // enable animation
	 * REDIPS.drag.shift.animation = true;
	 * // shift content
	 * REDIPS.drag.shiftCells(lastCell, firstCell);
	 * @public
	 * @function
	 * @name REDIPS.drag#shiftCells
	 * @see <a href="#shift">shift.mode</a>
	 */
	shiftCells = function (td1, td2) {
		var tbl1, tbl2,	// table reference of source and target cell
			pos,		// start cell (source) position
			pos1,		// start position (used for settings of pos variable)
			pos2,		// end cell (target) position
			d,			// direction (1 - left, -1 - right)
			cl,			// cell list in form of [row, cell] -> table_cell (it takes care about rowspan and colspan)
			t1, t2,		// temporary source and target cell needed for relocate
			c1, c2,		// source and target cell needed for relocate
			m1, m2,		// set flags if source or target cell contains "mark" class name
			p2,			// remember last possible cell when marked cell occures
			shiftMode,	// shift.mode read from public parameter
			rows,		// row number
			cols,		// column number (column number is defined from first row)
			x, y,		// column / row
			max,
			overflow = false,	// (boolean) overflow flag (initially is set to false)
			myShift,			// shift method used locally in shiftCells
			handleOverflow;		// overflow method used locally (handler overflowed cells)
		// define myShift local method (content will be shifted with or without animation)
		myShift = function (source, target) {
			if (REDIPS.drag.shift.animation) {
				relocate(source, target, 'animation');
			}
			else {
				relocate(source, target);
			}
		};
		// handleOverflow - how to handle overflow content
		handleOverflow = function (target) {
			if (REDIPS.drag.shift.overflow === 'delete') {
				emptyCell(target);
			}
			// relocate overflowed content
			else if (REDIPS.drag.shift.overflow === 'source') {
				myShift(target, td.source);
			}
			else if (typeof(REDIPS.drag.shift.overflow) === 'object') {
				myShift(target, REDIPS.drag.shift.overflow);
			}
			// set overflow flag to false (overflow could happen only once)
			overflow = false;
			// call shiftOverflow event handler
			REDIPS.drag.event.shiftOverflow(target);
		};
		// if DIV element is dropped to the source cell then there's nothing to do - just return from method
		if (td1 === td2) {
			return;
		}
		// set shift.mode from public property
		shiftMode = REDIPS.drag.shift.mode;
		// set table reference for source and target table cell
		tbl1 = findParent('TABLE', td1);
		tbl2 = findParent('TABLE', td2);
		// prepare cell index (this will take care about rowspan and cellspan cases)
		cl = cellList(tbl2);
		// set source position only if both locations are from the same table
		if (tbl1 === tbl2) {
			pos1 = [td1.redips.rowIndex, td1.redips.cellIndex];
		}
		else {
			pos1 = [-1, -1];
		}
		// set source and target position (pos1 is used for setting pos variable in switch (shiftMode) case)
		pos2 = [td2.redips.rowIndex, td2.redips.cellIndex];
		// define number of rows and columns for target table (it's used as row and column index) 
		rows = tbl2.rows.length;
		cols = maxCols(tbl2);
		// set start position for shifting (depending on shift.mode value)
		switch (shiftMode) {
		case 'vertical2':
			// if source and target are from the same table and from the same column then use pos1 otherwise set last cell in column
			pos = (tbl1 === tbl2 && td1.redips.cellIndex === td2.redips.cellIndex) ? pos1 : [rows, td2.redips.cellIndex];
			break;
		case 'horizontal2':
			// if source and target are from the same table and from the same row then use pos1 otherwise set last cell in row
			pos = (tbl1 === tbl2 && td1.parentNode.rowIndex === td2.parentNode.rowIndex) ? pos1 : [td2.redips.rowIndex, cols];
			break;
		// vertical1 and horizontal1 shift.mode
		default:
			// set start cell if source and target cells are from the same table otherwise set last cell in table
			pos = (tbl1 === tbl2) ? pos1 : [rows, cols];
		}
		//
		// shift direction, max and row / column variables
		//
		// set direction (up/down) for vertical shift.mode
		// if source cell is prior to the target cell then set direction to the "up", otherwise direction is to the "down"
		if (shiftMode === 'vertical1' || shiftMode === 'vertical2') {
			d = (pos[1] * 1000 + pos[0] < pos2[1] * 1000 + pos2[0]) ? 1 : -1;
			max = rows;
			x = 0;
			y = 1;
		}
		// set direction (left/right) for horizontal shift.mode
		// if source cell is prior to the target cell then set direction to the "left", otherwise direction is to the "right"
		else {
			d = (pos[0] * 1000 + pos[1] < pos2[0] * 1000 + pos2[1]) ? 1 : -1;
			max = cols;
			x = 1;
			y = 0;
		}
		//
		// set overflow flag
		//
		// if source and target tables are different or max cell is defined for row, column or table then set possible overflow
		if (pos[0] !== pos1[0] && pos[1] !== pos1[1]) {
			overflow = true;
		}
		//
		// loop
		//
		// while loop - goes from target to source position (backward)
		// imagine row with 5 cells, relocation will go like this: 3->4, 2->3, 1->2 and 0->1 
		while (pos[0] !== pos2[0] || pos[1] !== pos2[1]) {
			// define target cell
			t2 = cl[pos[0] + '-' + pos[1]];
			// increase indexes for row and column to define source cell 
			// increment row index
			pos[x] += d;
			// if row is highest row
			if (pos[x] < 0) {
				pos[x] = max;
				pos[y]--;
			}
			// if cellIndex was most right column
			else if (pos[x] > max) {
				pos[x] = 0;
				pos[y]++;
			}
			// define temp source cell
			t1 = cl[pos[0] + '-' + pos[1]];
			// if temp1 cell source cell exists then remember location to c1
			if (t1 !== undefined) {
				c1 = t1;
			}
			// if temp2 cell source cell exists then remember location to c2
			if (t2 !== undefined) {
				c2 = t2;
			}
			// shift DIV if exists (t1 and c2) or (c1 and t2)
			if ((t1 !== undefined && c2 !== undefined) || (c1 !== undefined && t2 !== undefined)) {
				// set "mark" flags if source or target cell contains "mark" class name
				m1 = c1.className.indexOf(REDIPS.drag.mark.cname) === -1 ? 0 : 1;
				m2 = c2.className.indexOf(REDIPS.drag.mark.cname) === -1 ? 0 : 1;
				// detect overflow (actually this is detection of first allowed cell)
				if (overflow) {
					// if target cell is marked and source cell is not marked handle overflow (overflow flag will be automatically set to false)
					if (m1 === 0 && m2 === 1) {
						handleOverflow(c1);
					}
				}
				// if source cell is forbidden then skip shifting
				if (m1 === 1) {
					// if target cell isn't foribdden then remember this location
					if (m2 === 0) {
						p2 = c2;
					}
					continue;
				}
				// set target cell to be last free cell (remembered in previous step)
				else if (m1 === 0 && m2 === 1) {
					c2 = p2;
				}
				// relocate cell content with or without animation
				myShift(c1, c2);
			}
			// overflow detection (fall off table edge)
			else if (overflow && c1 !== undefined && c2 === undefined) {
				// test for "mark" class name for source cell
				m1 = c1.className.indexOf(REDIPS.drag.mark.cname) === -1 ? 0 : 1;
				// if edge cell is not marked then handle overflow
				if (m1 === 0) {
					handleOverflow(c1);
				}
			}
		}
	};


	/**
	 * Determining a table cell's X and Y position/index.
	 * @see <a href="http://www.javascripttoolbox.com/temp/table_cellindex.html">http://www.javascripttoolbox.com/temp/table_cellindex.html</a>
	 * @see <a href="http://www.barryvan.com.au/2012/03/determining-a-table-cells-x-and-y-positionindex/">http://www.barryvan.com.au/2012/03/determining-a-table-cells-x-and-y-positionindex/</a>
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	cellList = function (table) {
		var matrix = [],
			matrixrow,
			lookup = {},
			c,			// current cell
			ri,			// row index
			rowspan,
			colspan,
			firstAvailCol,
			tr,			// TR collection
			i, j, k, l;	// loop variables
		// set HTML collection of table rows
		tr = table.rows;
		// open loop for each TR element
		for (i = 0; i < tr.length; i++) {
			// open loop for each cell within current row
			for (j = 0; j < tr[i].cells.length; j++) {
				// define current cell
				c = tr[i].cells[j];
				// set row index
				ri = c.parentNode.rowIndex;
				// define cell rowspan and colspan values
				rowspan = c.rowSpan || 1;
				colspan = c.colSpan || 1;
				// if matrix for row index is not defined then initialize array
				matrix[ri] = matrix[ri] || [];
				// find first available column in the first row
				for (k = 0; k < matrix[ri].length + 1; k++) {
					if (typeof(matrix[ri][k]) === 'undefined') {
						firstAvailCol = k;
						break;
					}
				}
				// set cell coordinates and reference to the table cell
				lookup[ri + '-' + firstAvailCol] = c;
				// create a "property object" in which "real" row/cell index will be saved
				if (c.redips === undefined) {
					c.redips = {};
				}
				// save row and cell index to the cell
				c.redips.rowIndex = ri;
				c.redips.cellIndex = firstAvailCol;
				for (k = ri; k < ri + rowspan; k++) {
					matrix[k] = matrix[k] || [];
					matrixrow = matrix[k];
					for (l = firstAvailCol; l < firstAvailCol + colspan; l++) {
						matrixrow[l] = 'x';
					}
				}
			}
		}
		return lookup;
	};


	/**
	 * Method returns number of maximum columns in table (some row may contain merged cells).
	 * @param {HTMLElement|String} table TABLE element.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	maxCols = function (table) {
		var	tr = table.rows,	// define number of rows in current table
			span,				// sum of colSpan values
			max = 0,			// maximum number of columns
			i, j;				// loop variable
		// if input parameter is string then overwrite it with table reference
		if (typeof(table) === 'string') {
			table = document.getElementById(table);
		}
		// open loop for each TR within table
		for (i = 0; i < tr.length; i++) {
			// reset span value
			span = 0;
			// sum colspan value for each table cell
			for (j = 0; j < tr[i].cells.length; j++) {
				span += tr[i].cells[j].colSpan || 1;
			}
			// set maximum value
			if (span > max) {
				max = span;
			}
		}
		// return maximum value
		return max;
	};


	/**
	 * Method will calculate parameters and start animation (DIV element to the target table cell).
	 * "moveObject" will always move DIV element with animation while "relocate" has option to relocate all DIV elements from one TD to another TD with or without animation.
	 * If "target" property is not defined then current location will be used. Here is properties definition of input parameter:
	 * <ul>
	 * <li>{String} id - id of element to animate - DIV element or row handler (div class="drag row")</li>
	 * <li>{String} obj - reference of element to animate - DIV element or row handler (if "id" parameter exists, "obj" parameter will be ignored)</li>
	 * <li>{String} mode - animation mode (if mode="row" then source and target properties should be defined)</li>
	 * <li>{Boolean} clone - if set to true then DIV element will be cloned instead of moving (used only in "cell" mode and default is false)</li>
	 * <li>{Boolean} overwrite - if set to true then elements in target cell will be overwritten (used only in "cell" mode and default is false)</li>
	 * <li>{Array} source - source position (table index and row index)</li>
	 * <li>{Array} target - target position (table, row and cell index (optional for "row" mode)</li>
	 * <li>{Function} callback - callback function executed after animation is finished</li>
	 * </ul>
	 * Method returns array containing reference of two object. In "cell" mode returned objects are:
	 * <ul>
	 * <li>Array[0] - dragged element</li>
	 * <li>Array[1] - dragged element</li>
	 * </ul>
	 * In "row" mode returned objects are:
	 * <ul>
	 * <li>Array[0] - tableMini</li>
	 * <li>Array[1] - source row</li>
	 * </ul>
	 * If "clone" parameter is set to true then event.cloned() event handler will be invoked with input parameter of cloned element.
	 * @param {Object} ip Object with properties: id, mode, source, target and callback.
	 * @return {Array|Boolean} Returns reference of two elements in array or false. In "cell" mode both elements are dragged element, while in "row" mode first element is tableMini and second element is source row or it could be false if "emptyRow" try to move.
	 * @example
	 * // move element with id="a1" to the current location and after
	 * // animation is finished display alert "Finished"  
	 * rd.moveObject({
	 *     id: 'a1',
	 *     callback: function () {
	 *         alert('Finished');
	 *     }
	 * });
	 *  
	 * // move DIV element with reference "mydiv" to the TD with reference td
	 * rd.moveObject({
	 *     obj: mydiv,
	 *     target: td
	 * });
	 *  
	 * // move DIV element with reference "mydiv" to the first table, second row and third cell
	 * rd.moveObject({
	 *     obj: mydiv,
	 *     target: [0, 1, 2]
	 * });
	 *  
	 * // move element with id="a2" to the first table, second row and third cell
	 * rd.moveObject({
	 *     id: 'a2',
	 *     target: [0, 1, 2]
	 * });
	 *  
	 * // clone DIV element with reference "mydiv", move to the first table, second row,
	 * // third cell and overwrite all content in target cell
	 * rd.moveObject({
	 *     obj: mydiv,
	 *     clone: true,
	 *     overwrite: true,
	 *     target: [0, 1, 2]
	 * });
	 *  
	 * // move first row and after animation is finished call "enable_button" function
	 * // "moveObject" returns Array with references of tableMini and source row
	 * row = rd.moveObject({
	 *           mode: 'row',            // animation mode - row
	 *           source: [0, 0],         // source position (table index and row index)
	 *           target: [0, 6],         // target position
	 *           callback: enable_button // function to call after animation is over
	 *        });
	 * @see <a href="#relocate">relocate</a>
	 * @public
	 * @function
	 * @name REDIPS.drag#moveObject
	 */
	moveObject = function (ip) {
		var p = {'direction': 1},	// param object (with default direction)
			x1, y1,	w1, h1,			// coordinates and width/height of object to animate
			x2, y2,	w2, h2,			// coordinates and width/height of target cell
			row, col,				// row and cell indexes
			dx, dy,					// delta x and delta y
			pos, i,					// local variables needed for calculation coordinates and settings the first point
			target;
		// set callback function - it will be called after animation is finished
		p.callback = ip.callback;
		// set overwrite parameter
		p.overwrite = ip.overwrite;
		// define obj and objOld (reference of the object to animate - DIV element or row handler)
		// ip.id - input parameter obj_id
		if (typeof(ip.id) === 'string') {
			p.obj = p.objOld = document.getElementById(ip.id);
		}
		// reference of DIV element to animate
		else if (typeof(ip.obj) === 'object' && ip.obj.nodeName === 'DIV') {
			p.obj = p.objOld = ip.obj;
		}
		// test if animation mode is "row" (mode, source and target properties should be defined)
		if (ip.mode === 'row') {
			p.mode = 'row';
			// find table index for source table (source[0] contains original table index)
			i = getTableIndex(ip.source[0]);
			// define source row index from input parameter object
			row = ip.source[1];
			// set source row
			objOld = p.objOld = tables[i].rows[row];
			// if row is marked as empty row then it will not be moved and method will return false
			if (objOld.redips && objOld.redips.emptyRow === true) {
				return false;
			}
			// set reference to the mini table - cloned from source row (TABLE element)
			p.obj = rowClone(p.objOld, 'animated');
		}
		// test if element is row handler
		else if (p.obj && p.obj.className.indexOf('row') > -1) {
			p.mode = 'row';
			// find TR element and remember reference to the source row (TR element)
			p.obj = p.objOld = objOld = findParent('TR', p.obj);
			// if row is marked as empty row then it will not be moved and method will return false
			if (objOld.redips && objOld.redips.emptyRow === true) {
				return false;
			}
			// set reference to the mini table - cloned from source row (TABLE element)
			p.obj = rowClone(p.objOld, 'animated');
		}
		// animation mode is "cell"
		else {
			p.mode = 'cell';
		}
		// p.obj should be existing object (null or non objects are not allowed)
		if (typeof(p.obj) !== 'object' || p.obj === null) {
			return;
		}
		// set high z-index
		p.obj.style.zIndex = 999;
		// if clicked element doesn't belong to the current container then context should be changed
		// redips property could not be set in case when static DIV is moved (like in example25)
		if (p.obj.redips && dragContainer !== p.obj.redips.container) {
			dragContainer = p.obj.redips.container;
			initTables();
		}
		// set width, height and coordinates for source position of object
		pos = boxOffset(p.obj);
		w1 = pos[1] - pos[3];
		h1 = pos[2] - pos[0];
		x1 = pos[3];
		y1 = pos[0];
		// if input parameter "clone" is true and DIV element is moving then clone DIV element instead of moving original element
		// this should go after definition of start coordinates x1 and y1
		if (ip.clone === true && p.mode === 'cell') {
			// clone object (DIV element)
			p.obj = cloneObject(p.obj, true);
			// and call event.cloned event handler
			REDIPS.drag.event.cloned(p.obj);
		}
		// if target parameted is undefined then use current position in table 
		if (ip.target === undefined) {
			ip.target = getPosition();
		}
		// if target is TD (object) then set position for this TD
		else if (typeof(ip.target) === 'object' && ip.target.nodeName === 'TD') {
			ip.target = getPosition(ip.target);
		}
		// set target table, row and cell indexes (needed for moving table row)
		// table index is index from array not original table index
		p.target = ip.target;
		// find table index because tables array is sorted on every element click (target[0] contains original table index)
		i = getTableIndex(ip.target[0]);
		// set index for row and cell (target input parameter is array)
		row = ip.target[1];
		col = ip.target[2];
		// if target row index is greater then number of rows in target table then set last row index
		if (row > tables[i].rows.length - 1) {
			row = tables[i].rows.length - 1;
		}
		// save reference of target cell
		p.targetCell = tables[i].rows[row].cells[col];
		// set width, height and coordinates of target cell
		if (p.mode === 'cell') {
			pos = boxOffset(p.targetCell);
			w2 = pos[1] - pos[3];
			h2 = pos[2] - pos[0];
			// target coordinates are cell center including object dimensions
			x2 = pos[3] + (w2 - w1) / 2;
			y2 = pos[0] + (h2 - h1) / 2;
		}
		// set width, height and coordinates of target row
		else {
			pos = boxOffset(tables[i].rows[row]);
			w2 = pos[1] - pos[3];
			h2 = pos[2] - pos[0];
			x2 = pos[3];
			y2 = pos[0];
		}
		// calculate delta x and delta y
		dx = x2 - x1;
		dy = y2 - y1;
		// set style to fixed to allow moving DIV object
		p.obj.style.position = 'fixed';
		// if line is more horizontal
		if (Math.abs(dx) > Math.abs(dy)) {
			// set path type
			p.type = 'horizontal';
			// set slope (m) and y-intercept (b)
			// y = m * x + b
			p.m = dy / dx;
			p.b = y1 - p.m * x1;
			// parameters needed for delay calculation (based on parabola)
			p.k1 = (x1 + x2) / (x1 - x2);
			p.k2 = 2 / (x1 - x2);
			// define animation direction
			if (x1 > x2) {
				p.direction = -1;
			}
			// set first and last point
			i = x1;
			p.last = x2;
		}
		// line is more vertical
		else {
			// set path type
			p.type = 'vertical';
			// set slope (m) and y-intercept (b)
			// y = m * x + b
			p.m = dx / dy;
			p.b = x1 - p.m * y1;
			// parameters needed for delay calculation (based on parabola)
			p.k1 = (y1 + y2) / (y1 - y2);
			p.k2 = 2 / (y1 - y2);
			// define animation direction
			if (y1 > y2) {
				p.direction = -1;
			}
			// set first and last point
			i = y1;
			p.last = y2;
		}
		// set attribute "animated" of DIV object to true (to disable dragging od DIV while animation lasts)
		// redips property could not be set in case when static DIV is moved (like in example25)
		if (p.obj.redips) {
			p.obj.redips.animated = true;
		}
		// start animation
		animateObject(i, p);
		// return reference of obj and objOld elements
		// "cell" mode
		// obj - dragged element
		// objOld - dragged element
		// "row" mode
		// obj - tableMini
		// objOld - source row
		return [p.obj, p.objOld];
	};


	/**
	 * Element (DIV or table row) animation.
	 * After "moveObject" calculates parameters, animation is started by calling "animate" method.
	 * Each other animation step is done by recursive calls until element reaches last point.
	 * Input parameters are first (current) point and 'p' object with following properties:
	 * <ul>
	 * <li>obj - object to animate</li>
	 * <li>targetCell - target table cell</li>
	 * <li>last - last point</li>
	 * <li>m, b - slope and y-intercept (needed for y = m * x + b)</li>
	 * <li>k1, k2 - constants needed for calculation 1 -> 0 -> 1 parameter (regarding current position)</li>
	 * <li>direction - animation direction (1 or -1)</li>
	 * <li>type - line type (horizontal or vertical)</li>
	 * <li>overwrite - if set to true then elements in target cell will be overwritten (used only in "cell" mode and default is false)</li>
	 * </ul>
	 * @param {Integer} i First (and lately current) point
	 * @param {Object} p Object with properties: obj, targetCell, last, m, b, k1, k2, direction and type
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	animateObject = function (i, p) {
		// calculate parameter k (k goes 1 -> 0 -> 1 for start and end step)
		var k = (p.k1 - p.k2 * i) * (p.k1 - p.k2 * i),
			f;
		// calculate step and function of step (y = m * x + b)
		i = i + REDIPS.drag.animation.step * (4 - k * 3) * p.direction;
		f = p.m * i + p.b;
		// set element position
		if (p.type === 'horizontal') {
			p.obj.style.left = i + 'px';
			p.obj.style.top  = f + 'px';			
		}
		else {
			p.obj.style.left = f + 'px';
			p.obj.style.top  = i + 'px';
		}
		// if line is not finished then make recursive call
		if ((i < p.last && p.direction > 0) || ((i > p.last) && p.direction < 0)) {
			// recursive call for next step
			setTimeout(function () {
				animateObject(i, p);
			}, REDIPS.drag.animation.pause * k);
		}
		// animation is finished
		else {
			// reset object styles
			resetStyles(p.obj);
			// set animation flag to false to enable DIV dragging
			// redips property could not be set in case when static DIV is moved (like in example25)
			if (p.obj.redips) {
				p.obj.redips.animated = false;
			}
			// if moved element is cell then append element to the target cell
			if (p.mode === 'cell') {
				// if overwrite parameter is set to true then empty targetCell
				if (p.overwrite === true) {
					// empty target cell
					emptyCell(p.targetCell);
				}
				p.targetCell.appendChild(p.obj);
				// register event listeners (FIX for Safari Mobile) if DIV element is not disabled
				if (p.obj.redips && p.obj.redips.enabled !== false) {
					registerEvents(p.obj);
				}
			}
			// else element is row
			else {
				// take care about real table index
				rowDrop(getTableIndex(p.target[0]), p.target[1], p.obj);
			}
			// execute callback function if callback is defined and send reference of moved element
			if (typeof(p.callback) === 'function') {
				p.callback(p.obj);
			}
		}
	};


	/**
	 * Method returns position as array with members tableIndex, rowIndex and cellIndex (array length is 3).
	 * If input parameter is not defined then method will return array with current and source positions (array length will be 6).
	 * @param {String|HTMLElement} [ip] DIV element id / reference or table cell id / reference.
	 * @return {Array} Returns array with members tableIndex, rowIndex and cellIndex. If position is not found then all array members will have value -1.
	 * @example
	 * // set REDIPS.drag reference
	 * var rd = REDIPS.drag;
	 * // display target and source position of dropped element
	 * rd.event.dropped = function () {
	 *    // get target and source position (method returns positions as array)
	 *    // pos[0] - target table index
	 *    // pos[1] - target row index
	 *    // pos[2] - target cell (column) index
	 *    // pos[3] - source table index
	 *    // pos[4] - source row index
	 *    // pos[5] - source cell (column) index
	 *    var pos = rd.getPosition();
	 *    // display element positions
	 *    console.log(pos);
	 * };
	 * @public
	 * @function
	 * @name REDIPS.drag#getPosition
	 */
	getPosition = function (ip) {
		var toi,		// table original index (because tables are sorted on every element click)
			toi_source,	// table original index (source table)
			ci, ri, ti,	// cellIndex, rowIndex and table index (needed for case if input parameter exists)
			el,			// element reference
			tbl,		// table reference
			arr = [];	// array to return
		// set initial values for cell, row and table index
		ci = ri = ti = -1;
		// if input parameter is is undefined, then return current location and source location (array will contain 6 elements)
		if (ip === undefined) {
			// table original index (because tables are sorted on every element click)
			if (table < tables.length) {
				toi = tables[table].redips.idx;
			}
			// if any level of old position is undefined, then use source location
			else if (table_old === null || row_old === null || cell_old === null) {
				toi = tables[table_source].redips.idx;
			}
			// or use the previous location
			else {
				toi = tables[table_old].redips.idx;
			}
			// table source original index
			toi_source = tables[table_source].redips.idx;
			// prepare array to return (row, cell and row_source, cell_source are global variables)
			arr = [toi, row, cell, toi_source, row_source, cell_source];
		}
		// input parameter is defined (id or reference of table cell or any child of table cell) 
		else {
			// if input parameter is string (this should be element id), then set element reference
			if (typeof(ip) === 'string') {
				el = document.getElementById(ip);
			}
			// else, input parameter is reference
			else {
				el = ip;
			}
			// if element exists
			if (el) {
				// find parent TD element (because "ip" could be the child element of table cell - DIV drag or any other inner element)
				if (el.nodeName !== 'TD') {
					el = findParent('TD', el);
				}
				// if node is table cell then set coordinates
				if (el && el.nodeName === 'TD') {
					// define cellIndex and rowIndex 
					ci = el.cellIndex;
					ri = el.parentNode.rowIndex;
					// find table
					tbl = findParent('TABLE', el);
					// define table index
					ti = tbl.redips.idx;
				}
			}
			// prepare array with tableIndex, rowIndex and cellIndex (3 elements)
			arr = [ti, ri, ci];
		}
		// return result array
		return arr;
	};
	

	/**
	 * Find table index - because tables array is sorted on every element click.
	 * @param {Integer} idx Table index of initial table order.
	 * @return {Integer} Returns current index from tables array.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	getTableIndex = function (idx) {
		var i;
		for (i = 0; i < tables.length; i++) {
			if (tables[i].redips.idx === idx) {
				break;
			}
		}
		return i;
	};


	/**
	 * Function returns a string in which all of the preceding and trailing white space has been
	 * removed, and in which all internal sequences of white is replaced with one white space. 
	 * @param {String} str Input string.
	 * @return {String} Returns normalized string.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	normalize = function (str) {
		if (str !== undefined) {
			str = str.replace(/^\s+|\s+$/g, '').replace(/\s{2,}/g, ' ');
		}
		// return normalized string (without preceding and trailing spaces)
		return str;
	};


	/**
	 * Method returns "true" if input element contains child nodes with nodeType === 1.
	 * Other node types (like text node) are ignored.
	 * @param {HTMLElement} el Input element.
	 * @return {Boolean} Returns true or false.
	 * @private
	 * @memberOf REDIPS.drag#
	 */
	hasChilds = function (el) {
		// local variable
		var i;
		// loop goes through all child nodes and search for node with nodeType === 1
		for (i = 0; i < el.childNodes.length; i++) {
			if (el.childNodes[i].nodeType === 1) {
				return true;
			}
		}
		return false;
	};


	/**
	 * Method sets opacity to table row or deletes row content.
	 * Input parameter "el" is reference to the table row or reference to the cloned mini table (when row is moved).
	 * @param {HTMLElement|String} el Id of row handler (div class="drag row") or reference to element (source row or mini table).
	 * @param {Integer|String} opacity Opacity level (from 0 to 100) or "empty" (then content of table cells in row will be deleted - in that case first parameter should be TR).
	 * @param {String} [color] Background color.
	 * @example
	 * // set reference to the REDIPS.drag library
	 * rd = REDIPS.drag; 
	 * 
	 * // make row semi-transparent
	 * rd.rowOpacity(rowObj, 50);
	 * 
	 * // set row as empty and white (content in table cells will be deleted)
	 * rd.rowOpacity(rowObj, 'empty', 'White');
	 * @public
	 * @function
	 * @name REDIPS.drag#rowOpacity
	 */
	rowOpacity = function (el, opacity, color) {
		var	tdNodeList,	// table cells
			i, j;		// loop variables
		// if input parameter is string (this should be element id), then set element reference
		if (typeof(el) === 'string') {
			el = document.getElementById(el);
			// el could be reference of the DIV class="drag row" (row handler)
			el = findParent('TABLE', el);
		}
		// if el is TR, then set background color to each cell (if needed) and apply opacity
		if (el.nodeName === 'TR') {
			// collect table cell from the row
			tdNodeList = el.getElementsByTagName('td');
			// set opacity for DIV element
			for (i = 0; i < tdNodeList.length; i++) {
				// set background color to table cell if needed
				tdNodeList[i].style.backgroundColor = color ? color : '';
				// if opacity is set to "empty" then delete cell content 
				if (opacity === 'empty') {
					tdNodeList[i].innerHTML = '';
				}
				// otherwise set opacity to every child node in table cell
				else {
					// loop through child nodes of every table cell
					for (j = 0; j < tdNodeList[i].childNodes.length; j++) {
						// apply styles only to Element nodes (not text nodes, attributes ...)
						// http://code.stephenmorley.org/javascript/dom-nodetype-constants/
						if (tdNodeList[i].childNodes[j].nodeType === 1) {
							tdNodeList[i].childNodes[j].style.opacity = opacity / 100;
							tdNodeList[i].childNodes[j].style.filter = 'alpha(opacity=' + opacity + ')';
							//td[i].childNodes[j].style.visibility = 'hidden';
						}
					}
				}
			}
		}
		// when row is moved then REDIPS.drag will create mini table with one row
		// all browsers (IE8, Opera11, FF3.6, Chrome10) can set opacity to the table
		else {
			el.style.opacity = opacity / 100;					// set opacity for FF, Chrome, Opera
			el.style.filter = 'alpha(opacity=' + opacity + ')';	// set opacity for IE
			el.style.backgroundColor = color ? color : '';		// set background color 
		}
	};


	/**
	 * Method marks selected row as empty. Could be needed for displaying initially empty table.
	 * Input parameters are table id and row index.
	 * @param {String} tbl_id Table id.
	 * @param {Integer} row_idx Row index (starts from 0).
	 * @param {String} [color] Color of empty row (default is "White" or defined with REDIPS.drag.rowEmptyColor parameter).
	 * @see <a href="#style">style.rowEmptyColor</a>
	 * @example
	 * // set reference to the REDIPS.drag library
	 * rd = REDIPS.drag; 
	 * // mark first row as empty in table with id="tbl1"
	 * rd.rowEmpty('tbl1', 0);
	 * @public
	 * @function
	 * @name REDIPS.drag#rowEmpty
	 */
	rowEmpty = function (tbl_id, row_idx, color) {
		var tbl = document.getElementById(tbl_id),
			row = tbl.rows[row_idx];
		// define color parameter if input parameter "color" is not defined
		if (color === undefined) {
			color = REDIPS.drag.style.rowEmptyColor;
		}
		// create a "property object" in which all custom properties of row will be saved.
		if (row.redips === undefined) {
			row.redips = {};
		}
		// set emptyRow property to true
		row.redips.emptyRow = true;
		// mark row as empty
		rowOpacity(row, 'empty', color);
	};


	return {
		/* public properties */
		/**
		 * Currently moved DIV element. Reference to the REDIPS.drag.obj (dragged DIV element) is visible and can be used in appropriate event handlers.
		 * @type HTMLElement
		 * @name REDIPS.drag#obj
		 */
		obj	: obj,
		/**
		 * Previously moved DIV element (before clicked or cloned). In case when DIV element is cloned, obj is reference of current (cloned) DIV element while objOld is reference of bottom (origin) DIV element.
		 * @type HTMLElement
		 * @name REDIPS.drag#objOld
		 */
		objOld	: objOld,
		/**
		 * Dragging mode "cell" or "row" (readonly).
		 * This is readonly property defined in a moment when DIV element or row handler is clicked.
		 * @type String
		 * @name REDIPS.drag#mode
		 */
		mode : mode,
		/**
		 * Object contains reference to previous, source, current and target table cell. Td references can be used in event handlers. 
		 * <ul>
		 * <li>{HTMLElement} td.source - reference to source table cell (set in onmousedown)</li>
		 * <li>{HTMLElement} td.previous - reference to previous table cell (set in onmousemove and autoscroll)</li>
		 * <li>{HTMLElement} td.current - reference to current table cell (set in onmousemove and autoscroll)</li>
		 * <li>{HTMLElement} td.target - reference to target table cell (target table cell is set in a moment of dropping element to the table cell)</li>
		 * </ul>  
		 * @type Object
		 * @name REDIPS.drag#td
		 */
		td : td,
		/**
		 * Hover object contains 4 properties: colorTd, colorTr, borderTd and borderTr. colorTd and colorTr define hover color for DIV element and table row.
		 * If borderTd is defined, then highlighted cell will have border. If borderTr is defined then highlighted row will have only top or bottom border.
		 * Top border shows that row will be placed above current row, while bottom border shows that current row will be placed below current row.
		 * Some browsers may have problem with "border-collapse:collapse" table style and border highlighting.
		 * In that case try without collapsing TD borders (e.g set "border-spacing:0" and smaller "td.border-width").
		 * @type Object
		 * @name REDIPS.drag#hover
		 * @example
		 * // set "#9BB3DA" as hover color for TD
		 * REDIPS.drag.hover.colorTd = '#9BB3DA';
		 *  
		 * // or set "Lime" as hover color for TR
		 * REDIPS.drag.hover.colorTr = 'Lime';
		 *  
		 * // set red border for highlighted TD
		 * REDIPS.drag.hover.borderTd = '2px solid red';
		 */
		hover : hover,
		/**
		 * Scroll object contains properties needed for autoscroll option.
		 * <ul>
		 * <li>{Boolean} scroll.enable - Enable / disable autoscroll option. By default autoscroll is enabled but it can be usefull in some cases to completely turn off autoscroll (if application doesn't need autoscrolling page nor autoscrolling DIV container). Turning off autoscroll will speed up application because extra calculations will be skipped. Default is true</li>
		 * <li>{Integer} scroll.bound - Bound size for triggering page autoScroll or autoScroll of scrollable DIV container. Default value is 25 (px).</li>
		 * <li>{Integer} scroll.speed - Autoscroll pause in milliseconds. Default value is 20 (milliseconds).</li>
		 * </ul>
		 * @type Object
		 * @name REDIPS.drag#scroll
		 */
		scroll : scroll,
		/**
		 * Table cells marked with "only" class name can accept only defined DIV elements.
		 * Object contains:
		 * <ul>
		 * <li>{Array} div - defined DIV elements can be dropped only to the table cells marked with class name "only" (DIV id -> class name)</li>
		 * <li>{String} cname - class name of marked cells (default is "only")</li>
		 * <li>{String} other - allow / deny dropping DIV elements to other table cells (default is "deny")</li>
		 * </ul>
		 * @example
		 * // only element with Id "a1" can be dropped to the cell with class name "only last"
		 * REDIPS.drag.only.div.a1 = 'last';
		 *  
		 * // DIV elements mentioned in REDIPS.drag.only.div cannot be dropped to other cells
		 * REDIPS.drag.only.other = 'deny';
		 * @type Object
		 * @name REDIPS.drag#only
		 * @see <a href="#mark">mark</a>
		 */
		only : only,
		/**
		 * Table cells marked with "mark" class name can be allowed or forbidden for accessing (with exceptions) - default is "deny".
		 * This is useful to define table cells forbidden for every DIV element with exceptions (or contrary, define table cells allowed for all DIV elements except some).
		 * Object contains:
		 * <ul>
		 * <li>{String} action - allow / deny table cell (default is "deny")</li>
		 * <li>{String} cname - class name of marked cells (default is "mark")</li>
		 * <li>{Array} exception - defined DIV elements can be dropped to the table cells marked with class "mark" (DIV id -> class name)</li>
		 * </ul>
		 * @example
		 * // only element with Id "d8" can be dropped to the cell with class name "mark smile"
		 * REDIPS.drag.mark.exception.d8 = 'smile';
		 * @type Object
		 * @see <a href="#only">only</a>
		 * @name REDIPS.drag#mark
		 */
		mark : mark,
		/**
		 * Object contains styles (colors, opacity levels) for DIV elements and table rows.
		 * <ul>
		 * <li>{String} style.borderEnabled - Border style for enabled DIV elements. Default is "solid".</li>
		 * <li>{String} style.borderDisabled - Border style for disabled DIV elements. Default is "dotted".</li>
		 * <li>{Integer} style.opacityDisabled - Opacity level for disabled elements. Default is empty string.</li>
		 * <li>{String} style.rowEmptyColor - "Empty row" color. When last row from table is moved then this color will be set to "empty row". Default is "white".</li>
		 * </ul>
		 * @example
		 * // define border style for disabled elements
		 * REDIPS.drag.style.borderDisabled = 'dashed';
		 * @type Object
		 * @name REDIPS.drag#style
		 */
		style : style,
		/**
		 * Object contains td class name (where DIV elements can be deleted) and confirmation questions for deleting DIV element or table row.
		 * <ul>
		 * <li>{String} trash.className - Class name of td which will become trash can. Default value is "trash".</li>
		 * <li>{String} trash.question - If trash.question is set then popup will appear and ask to confirm element deletion. Default value is null.</li>
		 * <li>{String} trash.questionRow - If trash.questionRow is set then popup will appear and ask to confirm row deletion. Default value is null.</li>
		 * </ul>
		 * @example
		 * // confirm DIV element delete action 
		 * REDIPS.drag.trash.question = 'Are you sure you want to delete DIV element?';
		 *  
		 * // confirm row delete action 
		 * REDIPS.drag.trash.questionRow = 'Are you sure you want to delete table row?';  
		 * @type Object
		 * @name REDIPS.drag#trash
		 */
		trash : trash,
		/**
		 * Save content parameter name. Parameter name should be short because it will be repeated for every DIV element.
		 * It is irrelevant in case of JSON format.
		 * @type String
		 * @name REDIPS.drag#saveParamName
		 * @default p
		 */
		saveParamName : saveParamName,
		/**
		 * Property defines working types of REDIPS.drag library for dragging DIV elements: multiple, single, switch, switching, overwrite and shift.
		 * @type String
		 * @name REDIPS.drag#dropMode
		 * @default multiple
		 * @example
		 * // elements can be dropped to all table cells (multiple elements in table cell)
		 * REDIPS.drag.dropMode = 'multiple';
		 *  
		 * // elements can be dropped only to the empty table cells
		 * REDIPS.drag.dropMode = 'single';
		 *  
		 * // switch content
		 * REDIPS.drag.dropMode = 'switch';
		 *  
		 * // switching content continuously
		 * REDIPS.drag.dropMode = 'switching';
		 *  
		 * // overwrite content in table cell
		 * REDIPS.drag.dropMode = 'overwrite';
		 *  
		 * // shift table content after element is dropped or moved to trash cell
		 * REDIPS.drag.dropMode = 'shift';
		 */
		dropMode : dropMode,
		/**
		 * Property defines "top" or "bottom" position of dropped element in table cell (if cell already contains DIV elements).
		 * It has affect only in case of dropMode="multiple".
		 * @type String
		 * @name REDIPS.drag#multipleDrop
		 * @default bottom
		 * @example
		 * // place dropped elements to cell top
		 * REDIPS.drag.multipleDrop = 'top';
		 */
		multipleDrop : multipleDrop,
		/**
		 * Object defines several rules related to cloning DIV elements like enable cloning with shift key, enable returning cloned DIV element to its source and so on.
		 * Instead of moving, DIV element / row will be cloned and ready for dragging.
		 * Just press SHIFT key and try to drag DIV element / row.
		 * if clone.sendBack property set to true, cloned DIV element will be deleted when dropped to the cell containing its source clone element.
		 * If exists, "climit" class will be updated (increased by 1).
		 * clone.drop property defines placing cloned DIV element (dropped outside any table) to the last marked position.
		 * If this property is set to true, the cloned DIV element will be always placed to the table cell.  
		 * <ul>
		 * <li>{Boolean} clone.keyDiv - If set to true, all DIV elements on tables could be cloned with pressed SHIFT key. Default is false.</li>
		 * <li>{Boolean} clone.keyRow - If set to true, table rows could be cloned with pressed SHIFT key. Default is false.</li>
		 * <li>{Boolean} clone.sendBack - If set to true, cloned element can be returned to its source. Default is false.</li>
		 * <li>{Boolean} clone.drop - If set to true, cloned element will be always placed to the table (to the last possible cell) no matter if is dropped outside the table. Default is false.</li>
		 * </ul>
		 * @type Object
		 * @name REDIPS.drag#clone
		 */
		clone : clone,
		/**
		 * Object contains animation properties: pause and step.
		 * <ul>
		 * <li>{Integer} animation.pause - Animation pause (lower values means the animation will go faster). Default value is 20 (milliseconds).</li>
		 * <li>{Integer} animation.step - Value defines number of pixels in each step. Higher values means bigger step (faster animation) but with less smoothness. Default value is 2 (px).</li>
		 * </ul>
		 * @type Object
		 * @name REDIPS.drag#animation
		 */
		animation : animation,
		/**
		 * Object contains several properties: shift.after, shift.mode, shift.overflow and shift.animation.
		 * <ul>
		 * <li>{String} shift.after - how to shift table content after DIV element is dropped</li>
		 * <li>{String} shift.mode - shift modes (horizontal / vertical)</li>
		 * <li>{String|HTMLElement} shift.overflow - defines how to behave when DIV element falls off the end</li>
		 * <li>{Boolean} shift.animation - if set to true, table content will be relocated with animation - default is false</li>
		 * </ul>
		 *  
		 * shift.after option has the following values: "default", "delete" and "always" (this property will have effect only if dropMode is set to "shift"). Default value is "default".
		 * <ul>
		 * <li>default - table content will be shifted only if DIV element is dropped to the non empty cell</li>
		 * <li>delete - same as "default" + shift table content after DIV element is moved to trash</li>
		 * <li>always - table content will be always shifted</li>
		 * </ul>
		 *  
		 * shift.mode defines shift modes: "horizontal1", "horizontal2", "vertical1" and "vertical2". Default value is "horizontal1".
		 * <ul>
		 * <li>horizontal1 - horizontal shift (element shift can affect more rows)</li>
		 * <li>horizontal2 - horizontal shift (each row is treated separately)</li>
		 * <li>vertical1 - vertical shift (element shift can affect more columns)</li>
		 * <li>vertical2 - vertical shift (each column is treated separately)</li>
		 * </ul>
		 * 
		 * shift.overflow defines how to behave when DIV element falls off the end. Possible actions are: "bunch", "delete" and "source". Default value is "bunch".
		 * <ul>
		 * <li>bunch - overflow will stay in last cell</li>
		 * <li>delete - overflow will be deleted</li>
		 * <li>source - overflow will be moved to the source TD</li>
		 * <li>{HTMLElement} - overflow will be moved to user defined HTML element</li>
		 * </ul> 
		 * @example
		 * // DIV elements will be shifted vertically (each column is treated separately)
		 * REDIPS.drag.shift.mode = 'vertical2';
		 *  
		 * // delete overflowed DIV element
		 * REDIPS.drag.shift.overflow = 'delete';
		 * @type Object
		 * @see <a href="#dropMode">dropMode</a>
		 * @see <a href="#shiftCells">shiftCells</a>
		 * @name REDIPS.drag#shift
		 */
		shift : shift,
		/**
		 * Property defines working types of REDIPS.drag library for dragging table rows: before, after, switch and overwrite.
		 * <ul>
		 * <li>before - row will be dropped before highlighted row</li>
		 * <li>after - row will be dropped after highlighted row</li>
		 * <li>switch - source and highlighted rows will be switched</li>
		 * <li>overwrite - highlighted row will be overwritten</li>
		 * </ul>  
		 * Values "before" and "after" have effect only if row is dropped to other tables (not in current table).
		 * In case of only one table, after/before is defined relatively to source row position (middle row will be dropped before highlighted row if dragged to the table top or after highlighted row in other case).
		 * @type String
		 * @name REDIPS.drag#rowDropMode
		 * @default before
		 */
		rowDropMode : rowDropMode,
		/**
		 * Table sort is feature where tables inside drop container are sorted on each element click.
		 * Clicked DIV element defines table that should be placed on the array top.
		 * Tables order is important for highlighting current cell in case of nested tables.
		 * But sometimes this feature should be turned off when one table overlays the other using "position" style relative, fixed or absolute.
		 * @type Boolean
		 * @name REDIPS.drag#tableSort
		 * @default true
		 */
		tableSort : tableSort,
		/* public methods (documented in main code) */
		init : init,
		initTables : initTables,
		enableDrag : enableDrag,
		enableTable : enableTable,
		cloneObject : cloneObject,
		saveContent : saveContent,
		relocate : relocate,
		emptyCell : emptyCell,
		moveObject : moveObject,
		shiftCells : shiftCells,
		deleteObject : deleteObject,
		getPosition : getPosition,
		rowOpacity : rowOpacity,
		rowEmpty : rowEmpty,
		getScrollPosition : getScrollPosition,
		getStyle : getStyle,
		findParent : findParent,
		findCell : findCell,

		/* Event Handlers */
		/**
		 * All events are part of REDIPS.drag.event namespace.
		 * @type Object
		 * @ignore
		 */
		event : event

		/* Element Event Handlers */
		/**
		 * Event handler invoked if a mouse button is pressed down while the mouse pointer is over DIV element.
		 * @param {HTMLElement} [currentCell] Reference to the table cell of clicked element.
		 * @name REDIPS.drag#event:clicked
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if a mouse button is clicked twice while the mouse pointer is over DIV element.
		 * @name REDIPS.drag#event:dblClicked
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if element is moved from home position.
		 * @param {Boolean} [cloned] True if moved element is actually a cloned DIV. Needed for cases when obj or objOld should be used.
		 * @name REDIPS.drag#event:moved
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if mouse button is pressed down and released while the mouse pointer is over DIV element (element was not actually moved).
		 * Default threshold value is 7px, so if DIV element is moved within threshold value (background color of cell will not change) the same event handler will be called.
		 * @name REDIPS.drag#event:notMoved
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if element is dropped to the table cell.
		 * @param {HTMLElement} [targetCell] Target cell reference.
		 * @name REDIPS.drag#event:dropped
		 * @function
		 * @event
		 */		
		/**
		 * Event handler invoked if mouse button is released but before element is dropped to the table cell.
		 * If boolen "false" is returned from event handler then element drop will be canceled.
		 * Dragged element will be returned to the start position while cloned element will be deleted.
		 * @param {HTMLElement} [targetCell] Target cell reference.
		 * @name REDIPS.drag#event:droppedBefore
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if DIV elements are switched (dropMode is set to "switch").
		 * @param {HTMLElement} [targetCell] Reference to the target table cell.
		 * @name REDIPS.drag#event:switched
		 * @see <a href="#dropMode">dropMode</a>
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked after all DIV elements are relocated and before table is enabled (DIV elements enabled for dragging).
		 * This event can be triggered after single call of relocate() method or after all DIV elements are shifted in "shift" mode.
		 * It is called only if animation is turned on.
		 * @name REDIPS.drag#event:relocateEnd
		 * @see <a href="#relocate">relocate</a>
		 * @see <a href="#event:relocateBefore">event.relocateBefore</a>
		 * @see <a href="#event:relocateAfter">event.relocateAfter</a>
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked before DIV element will be relocated.
		 * For example, in shift drop mode, this event handler will be called before each DIV element move.
		 * @param {HTMLElement} div Reference of DIV element that will be relocated.
		 * @param {HTMLElement} td Reference of TD where DIV element will be relocated.
		 * @name REDIPS.drag#event:relocateBefore
		 * @see <a href="#relocate">relocate</a>
		 * @see <a href="#event:relocateAfter">event.relocateAfter</a>
		 * @see <a href="#event:relocateEnd">event.relocateEnd</a>
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked after DIV element is relocated.
		 * For example, in shift drop mode, this event handler will be called after each DIV element has been moved.
		 * @param {HTMLElement} div Reference of DIV element that is relocated.
		 * @param {HTMLElement} td Reference of TD where DIV element is relocated.
		 * @name REDIPS.drag#event:relocateAfter
		 * @see <a href="#relocate">relocate</a>
		 * @see <a href="#event:relocateBefore">event.relocateBefore</a>
		 * @see <a href="#event:relocateEnd">event.relocateEnd</a>
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked on every change of current (highlighted) table cell.
		 * @param {HTMLElement} [currentCell] Reference to the current (highlighted) table cell.
		 * @name REDIPS.drag#event:changed
		 * @see <a href="#getPosition">getPosition</a>
		 * @example
		 * // set REDIPS.drag reference
		 * var rd = REDIPS.drag;
		 * // define event.changed handler  
		 * rd.event.changed = function () {
		 *   // get current position (method returns positions as array)
		 *   var pos = rd.getPosition();
		 *   // display current row and current cell
		 *   console.log('Changed: ' + pos[1] + ' ' + pos[2]);
		 * };
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked after DIV element is cloned - interactively by moving DIV element or by calling move_object() in "cell" mode with "clone" option.
		 * If event handler is called from move_object() then reference of cloned element is sent as input parameter.
		 * Otherwise, reference of cloned DIV element is set to REDIPS.drag.obj while reference of original element is set to REDIPS.drag.objOld public property.
		 * @param {HTMLElement} [clonedElement] Cloned element reference.
		 * @see <a href="#moveObject">moveObject</a>
		 * @name REDIPS.drag#event:cloned
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked after cloned DIV element is dropped.
		 * @param {HTMLElement} [targetCell] Reference to the target table cell.
		 * @name REDIPS.drag#event:clonedDropped
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if last element is cloned (type 1).
		 * Element has defined "climit1_X" class name where X defines number of elements to clone. Last element can be dragged.
		 * @name REDIPS.drag#event:clonedEnd1
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if last element is cloned (type 2).
		 * Element has defined "climit2_X" class name where X defines number of elements to clone. Last element can't be dragged and stays static.
		 * @name REDIPS.drag#event:clonedEnd2
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if cloned element is dropped on start position or cloned element is dropped outside current table with "clone.drop" property set to false.
		 * This event handler could be also invoked if "clone" type element is placed inside forbidden table cell.
		 * @see <a href="#clone">clone.drop</a>
		 * @name REDIPS.drag#event:notCloned
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if element is deleted (dropped to the "trash" table cell).
		 * @param {Boolean} [cloned] True if cloned element is directly moved to the trash (in one move). If cloned element is dropped to the table and then moved to the trash then "cloned" parameter will be set to false.
		 * @name REDIPS.drag#event:deleted
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if element is undeleted.
		 * After element is dropped to the "trash" table cell and trash.question property is not null then popup with set question will appear.
		 * Clicking on "Cancel" will undelete element and call this event handler.
		 * @see <a href="#trash">trash</a>
		 * @name REDIPS.drag#event:undeleted 
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked after any DIV element action.
		 * For example, if drop option is set to "multiple" (default drop mode) and DIV element is dropped to the table cell then the following order of event handlers will be performed:
		 * <ol>
		 * <li>event.droppedBefore</li>
		 * <li>event.dropped (only if event.droppedBefore doesn't return false)</li>
		 * <li>event.finish</li>
		 * <ol>
		 * So, event.finish will be called after deleting DIV element, cloning, switching and so on.
		 * Its main purpose is to execute some common code (like "cleaning") after any DIV element action.
		 * @name REDIPS.drag#event:finish
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked in a moment when overflow happen in shift mode.
		 * @param {HTMLElement} td Reference of TD where overflow happen.
		 * @name REDIPS.drag#event:shiftOverflow
		 * @see <a href="#dropMode">dropMode</a>
		 * @function
		 * @event
		 */

		/* Row Event Handlers */
		/**
		 * Event handler invoked if a mouse button is pressed down while the mouse pointer is over row handler (div class="drag row").
		 * @param {HTMLElement} [currentCell] Reference to the table cell of clicked element.
		 * @name REDIPS.drag#event:rowClicked
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if row is moved from home position.
		 * @name REDIPS.drag#event:rowMoved
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if a mouse button is pressed down and released while the mouse pointer is over row handler (row was not actually moved).
		 * @name REDIPS.drag#event:rowNotMoved
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked after dropping row to the table.
		 * @param {HTMLElement} [targetRow] Reference to the target row (dropped row).
		 * @param {HTMLElement} [sourceTable] Source table reference. If row is dropped to the same table then this reference and targetRow will be in correlation (actually "source table" contains targetRow).
		 * @param {Integer} [sourceRowIndex] Source row index.
		 * @name REDIPS.drag#event:rowDropped
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked in the moment when mouse button is released but before row is dropped to the table.
		 * @param {HTMLElement} [sourceTable] Source table reference.
		 * @param {Integer} [sourceRowIndex] Source row index.
		 * If boolen "false" is returned from event handler then row drop will be canceled. 
		 * @name REDIPS.drag#event:rowDroppedBefore
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if row is moved around and dropped to the home position.
		 * @param {HTMLElement} [targetCell] Reference to the target table cell.
		 * @name REDIPS.drag#event:rowDroppedSource
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked on every change of current (highlighted) table row.
		 * @param {HTMLElement} [currentCell] Reference to the current (highlighted) table cell.
		 * @name REDIPS.drag#event:rowChanged
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if table row is cloned.
		 * @name REDIPS.drag#event:rowCloned
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if cloned row is dropped to the source row.
		 * @name REDIPS.drag#event:rowNotCloned
		 * @function
		 * @event
		 */	
		/**
		 * Event handler invoked if row is deleted (dropped to the "trash" table cell).
		 * @name REDIPS.drag#event:rowDeleted
		 * @function
		 * @event
		 */
		/**
		 * Event handler invoked if row is undeleted.
		 * After row is dropped to the "trash" table cell and trash.questionRow property is not null then popup with set question will appear.
		 * Clicking on "Cancel" will undelete row and call this event handler.
		 * @see <a href="#trash">trash</a>
		 * @name REDIPS.drag#event:rowUndeleted 
		 * @function
		 * @event
		 */	

	}; // end of public (return statement)		
}());




// if REDIPS.event isn't already defined (from other REDIPS file) 
if (!REDIPS.event) {
	REDIPS.event = (function () {
		var add,	// add event listener
			remove;	// remove event listener
		
		// http://msdn.microsoft.com/en-us/scriptjunkie/ff728624
		// http://www.javascriptrules.com/2009/07/22/cross-browser-event-listener-with-design-patterns/
		// http://www.quirksmode.org/js/events_order.html

		// add event listener
		add = function (obj, eventName, handler) {
			if (obj.addEventListener) {
				// (false) register event in bubble phase (event propagates from from target element up to the DOM root)
				obj.addEventListener(eventName, handler, false);
			}
			else if (obj.attachEvent) {
				obj.attachEvent('on' + eventName, handler);
			}
			else {
				obj['on' + eventName] = handler;
			}
		};
	
		// remove event listener
		remove = function (obj, eventName, handler) {
			if (obj.removeEventListener) {
				obj.removeEventListener(eventName, handler, false);
			}
			else if (obj.detachEvent) {
				obj.detachEvent('on' + eventName, handler);
			}
			else {
				obj['on' + eventName] = null;
			}
		};
	
		return {
			add		: add,
			remove	: remove
		}; // end of public (return statement)	
		
	}());
}