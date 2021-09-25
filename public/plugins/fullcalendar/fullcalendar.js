(function(factory) {
	if (typeof define === 'function' && define.amd) {
		define([ 'jquery', 'moment' ], factory);
	}
	else {
		factory(jQuery, moment);
	}
})(function($, moment) {
;;
var defaults = {
	titleRangeSeparator: ' \u2014 ', 
	monthYearFormat: 'MMMM YYYY', 
	defaultTimedEventDuration: '02:00:00',
	defaultAllDayEventDuration: { days: 1 },
	forceEventDuration: false,
	nextDayThreshold: '09:00:00', 
	defaultView: 'month',
	aspectRatio: 1.35,
	header: {
		left: 'title',
		center: '',
		right: 'today prev,next'
	},
	weekends: true,
	weekNumbers: false,
	weekNumberTitle: 'W',
	weekNumberCalculation: 'local',
	lazyFetching: true,
	startParam: 'start',
	endParam: 'end',
	timezoneParam: 'timezone',
	timezone: false,
	isRTL: false,
	defaultButtonText: {
		prev: "prev",
		next: "next",
		prevYear: "prev year",
		nextYear: "next year",
		today: 'today',
		month: 'month',
		week: 'week',
		day: 'day'
	},
	buttonIcons: {
		prev: 'left-single-arrow',
		next: 'right-single-arrow',
		prevYear: 'left-double-arrow',
		nextYear: 'right-double-arrow'
	},
	theme: false,
	themeButtonIcons: {
		prev: 'circle-triangle-w',
		next: 'circle-triangle-e',
		prevYear: 'seek-prev',
		nextYear: 'seek-next'
	},
	dragOpacity: .75,
	dragRevertDuration: 500,
	dragScroll: true,
	unselectAuto: true,
	dropAccept: '*',
	eventLimit: false,
	eventLimitText: 'more',
	eventLimitClick: 'popover',
	dayPopoverFormat: 'LL',
	handleWindowResize: true,
	windowResizeDelay: 200 
};
var englishDefaults = {
	dayPopoverFormat: 'dddd, MMMM D'
};
var rtlDefaults = {
	header: {
		left: 'next,prev today',
		center: '',
		right: 'title'
	},
	buttonIcons: {
		prev: 'right-single-arrow',
		next: 'left-single-arrow',
		prevYear: 'right-double-arrow',
		nextYear: 'left-double-arrow'
	},
	themeButtonIcons: {
		prev: 'circle-triangle-e',
		next: 'circle-triangle-w',
		nextYear: 'seek-prev',
		prevYear: 'seek-next'
	}
};
;;
var fc = $.fullCalendar = { version: "2.2.5" };
var fcViews = fc.views = {};
$.fn.fullCalendar = function(options) {
	var args = Array.prototype.slice.call(arguments, 1); 
	var res = this; 
	this.each(function(i, _element) { 
		var element = $(_element);
		var calendar = element.data('fullCalendar'); 
		var singleRes; 
		if (typeof options === 'string') {
			if (calendar && $.isFunction(calendar[options])) {
				singleRes = calendar[options].apply(calendar, args);
				if (!i) {
					res = singleRes; 
				}
				if (options === 'destroy') { 
					element.removeData('fullCalendar');
				}
			}
		}
		else if (!calendar) { 
			calendar = new Calendar(element, options);
			element.data('fullCalendar', calendar);
			calendar.render();
		}
	});
	return res;
};
function setDefaults(d) {
	mergeOptions(defaults, d);
}
function mergeOptions(target) {
	function mergeIntoTarget(name, value) {
		if ($.isPlainObject(value) && $.isPlainObject(target[name]) && !isForcedAtomicOption(name)) {
			target[name] = mergeOptions({}, target[name], value); 
		}
		else if (value !== undefined) { 
			target[name] = value;
		}
	}
	for (var i=1; i<arguments.length; i++) {
		$.each(arguments[i], mergeIntoTarget);
	}
	return target;
}
function isForcedAtomicOption(name) {
	return /(Time|Duration)$/.test(name);
}
;;
var langOptionHash = fc.langs = {}; 
fc.datepickerLang = function(langCode, dpLangCode, dpOptions) {
	var fcOptions = langOptionHash[langCode] || (langOptionHash[langCode] = {});
	fcOptions.isRTL = dpOptions.isRTL;
	fcOptions.weekNumberTitle = dpOptions.weekHeader;
	$.each(dpComputableOptions, function(name, func) {
		fcOptions[name] = func(dpOptions);
	});
	if ($.datepicker) {
		$.datepicker.regional[dpLangCode] =
			$.datepicker.regional[langCode] = 
				dpOptions;
		$.datepicker.regional.en = $.datepicker.regional[''];
		$.datepicker.setDefaults(dpOptions);
	}
};
fc.lang = function(langCode, newFcOptions) {
	var fcOptions;
	var momOptions;
	fcOptions = langOptionHash[langCode] || (langOptionHash[langCode] = {});
	if (newFcOptions) {
		mergeOptions(fcOptions, newFcOptions);
	}
	momOptions = getMomentLocaleData(langCode); 
	$.each(momComputableOptions, function(name, func) {
		if (fcOptions[name] === undefined) {
			fcOptions[name] = func(momOptions, fcOptions);
		}
	});
	defaults.lang = langCode;
};
var dpComputableOptions = {
	defaultButtonText: function(dpOptions) {
		return {
			prev: stripHtmlEntities(dpOptions.prevText),
			next: stripHtmlEntities(dpOptions.nextText),
			today: stripHtmlEntities(dpOptions.currentText)
		};
	},
	monthYearFormat: function(dpOptions) {
		return dpOptions.showMonthAfterYear ?
			'YYYY[' + dpOptions.yearSuffix + '] MMMM' :
			'MMMM YYYY[' + dpOptions.yearSuffix + ']';
	}
};
var momComputableOptions = {
	dayOfMonthFormat: function(momOptions, fcOptions) {
		var format = momOptions.longDateFormat('l'); 
		format = format.replace(/^Y+[^\w\s]*|[^\w\s]*Y+$/g, '');
		if (fcOptions.isRTL) {
			format += ' ddd'; 
		}
		else {
			format = 'ddd ' + format; 
		}
		return format;
	},
	smallTimeFormat: function(momOptions) {
		return momOptions.longDateFormat('LT')
			.replace(':mm', '(:mm)')
			.replace(/(\Wmm)$/, '($1)') 
			.replace(/\s*a$/i, 'a'); 
	},
	extraSmallTimeFormat: function(momOptions) {
		return momOptions.longDateFormat('LT')
			.replace(':mm', '(:mm)')
			.replace(/(\Wmm)$/, '($1)') 
			.replace(/\s*a$/i, 't'); 
	},
	noMeridiemTimeFormat: function(momOptions) {
		return momOptions.longDateFormat('LT')
			.replace(/\s*a$/i, ''); 
	}
};
function getMomentLocaleData(langCode) {
	var func = moment.localeData || moment.langData;
	return func.call(moment, langCode) ||
		func.call(moment, 'en'); 
}
fc.lang('en', englishDefaults);
;;
fc.intersectionToSeg = intersectionToSeg;
fc.applyAll = applyAll;
fc.debounce = debounce;
function compensateScroll(rowEls, scrollbarWidths) {
	if (scrollbarWidths.left) {
		rowEls.css({
			'border-left-width': 1,
			'margin-left': scrollbarWidths.left - 1
		});
	}
	if (scrollbarWidths.right) {
		rowEls.css({
			'border-right-width': 1,
			'margin-right': scrollbarWidths.right - 1
		});
	}
}
function uncompensateScroll(rowEls) {
	rowEls.css({
		'margin-left': '',
		'margin-right': '',
		'border-left-width': '',
		'border-right-width': ''
	});
}
function disableCursor() {
	$('body').addClass('fc-not-allowed');
}
function enableCursor() {
	$('body').removeClass('fc-not-allowed');
}
function distributeHeight(els, availableHeight, shouldRedistribute) {
	var minOffset1 = Math.floor(availableHeight / els.length); 
	var minOffset2 = Math.floor(availableHeight - minOffset1 * (els.length - 1)); 
	var flexEls = []; 
	var flexOffsets = []; 
	var flexHeights = []; 
	var usedHeight = 0;
	undistributeHeight(els); 
	els.each(function(i, el) {
		var minOffset = i === els.length - 1 ? minOffset2 : minOffset1;
		var naturalOffset = $(el).outerHeight(true);
		if (naturalOffset < minOffset) {
			flexEls.push(el);
			flexOffsets.push(naturalOffset);
			flexHeights.push($(el).height());
		}
		else {
			usedHeight += naturalOffset;
		}
	});
	if (shouldRedistribute) {
		availableHeight -= usedHeight;
		minOffset1 = Math.floor(availableHeight / flexEls.length);
		minOffset2 = Math.floor(availableHeight - minOffset1 * (flexEls.length - 1)); 
	}
	$(flexEls).each(function(i, el) {
		var minOffset = i === flexEls.length - 1 ? minOffset2 : minOffset1;
		var naturalOffset = flexOffsets[i];
		var naturalHeight = flexHeights[i];
		var newHeight = minOffset - (naturalOffset - naturalHeight); 
		if (naturalOffset < minOffset) { 
			$(el).height(newHeight);
		}
	});
}
function undistributeHeight(els) {
	els.height('');
}
function matchCellWidths(els) {
	var maxInnerWidth = 0;
	els.find('> *').each(function(i, innerEl) {
		var innerWidth = $(innerEl).outerWidth();
		if (innerWidth > maxInnerWidth) {
			maxInnerWidth = innerWidth;
		}
	});
	maxInnerWidth++; 
	els.width(maxInnerWidth);
	return maxInnerWidth;
}
function setPotentialScroller(containerEl, height) {
	containerEl.height(height).addClass('fc-scroller');
	if (containerEl[0].scrollHeight - 1 > containerEl[0].clientHeight) { 
		return true;
	}
	unsetScroller(containerEl); 
	return false;
}
function unsetScroller(containerEl) {
	containerEl.height('').removeClass('fc-scroller');
}
function getScrollParent(el) {
	var position = el.css('position'),
		scrollParent = el.parents().filter(function() {
			var parent = $(this);
			return (/(auto|scroll)/).test(
				parent.css('overflow') + parent.css('overflow-y') + parent.css('overflow-x')
			);
		}).eq(0);
	return position === 'fixed' || !scrollParent.length ? $(el[0].ownerDocument || document) : scrollParent;
}
function getScrollbarWidths(container) {
	var containerLeft = container.offset().left;
	var containerRight = containerLeft + container.width();
	var inner = container.children();
	var innerLeft = inner.offset().left;
	var innerRight = innerLeft + inner.outerWidth();
	return {
		left: innerLeft - containerLeft,
		right: containerRight - innerRight
	};
}
function isPrimaryMouseButton(ev) {
	return ev.which == 1 && !ev.ctrlKey;
}
function intersectionToSeg(subjectRange, constraintRange) {
	var subjectStart = subjectRange.start;
	var subjectEnd = subjectRange.end;
	var constraintStart = constraintRange.start;
	var constraintEnd = constraintRange.end;
	var segStart, segEnd;
	var isStart, isEnd;
	if (subjectEnd > constraintStart && subjectStart < constraintEnd) { 
		if (subjectStart >= constraintStart) {
			segStart = subjectStart.clone();
			isStart = true;
		}
		else {
			segStart = constraintStart.clone();
			isStart =  false;
		}
		if (subjectEnd <= constraintEnd) {
			segEnd = subjectEnd.clone();
			isEnd = true;
		}
		else {
			segEnd = constraintEnd.clone();
			isEnd = false;
		}
		return {
			start: segStart,
			end: segEnd,
			isStart: isStart,
			isEnd: isEnd
		};
	}
}
function smartProperty(obj, name) { 
	obj = obj || {};
	if (obj[name] !== undefined) {
		return obj[name];
	}
	var parts = name.split(/(?=[A-Z])/),
		i = parts.length - 1, res;
	for (; i>=0; i--) {
		res = obj[parts[i].toLowerCase()];
		if (res !== undefined) {
			return res;
		}
	}
	return obj['default'];
}
var dayIDs = [ 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ];
var intervalUnits = [ 'year', 'month', 'week', 'day', 'hour', 'minute', 'second', 'millisecond' ];
function diffDayTime(a, b) {
	return moment.duration({
		days: a.clone().stripTime().diff(b.clone().stripTime(), 'days'),
		ms: a.time() - b.time() 
	});
}
function diffDay(a, b) {
	return moment.duration({
		days: a.clone().stripTime().diff(b.clone().stripTime(), 'days')
	});
}
function computeIntervalUnit(start, end) {
	var i, unit;
	for (i = 0; i < intervalUnits.length; i++) {
		unit = intervalUnits[i];
		if (computeIntervalAs(unit, start, end)) {
			break;
		}
	}
	return unit; 
}
function computeIntervalAs(unit, start, end) {
	var val;
	if (end != null) { 
		val = end.diff(start, unit, true);
	}
	else if (moment.isDuration(start)) { 
		val = start.as(unit);
	}
	else { 
		val = start.end.diff(start.start, unit, true);
	}
	if (val >= 1 && isInt(val)) {
		return val;
	}
	return false;
}
function isNativeDate(input) {
	return  Object.prototype.toString.call(input) === '[object Date]' || input instanceof Date;
}
function isTimeString(str) {
	return /^\d+\:\d+(?:\:\d+\.?(?:\d{3})?)?$/.test(str);
}
var hasOwnPropMethod = {}.hasOwnProperty;
function createObject(proto) {
	var f = function() {};
	f.prototype = proto;
	return new f();
}
function copyOwnProps(src, dest) {
	for (var name in src) {
		if (hasOwnProp(src, name)) {
			dest[name] = src[name];
		}
	}
}
function hasOwnProp(obj, name) {
	return hasOwnPropMethod.call(obj, name);
}
function isAtomic(val) {
	return /undefined|null|boolean|number|string/.test($.type(val));
}
function applyAll(functions, thisObj, args) {
	if ($.isFunction(functions)) {
		functions = [ functions ];
	}
	if (functions) {
		var i;
		var ret;
		for (i=0; i<functions.length; i++) {
			ret = functions[i].apply(thisObj, args) || ret;
		}
		return ret;
	}
}
function firstDefined() {
	for (var i=0; i<arguments.length; i++) {
		if (arguments[i] !== undefined) {
			return arguments[i];
		}
	}
}
function htmlEscape(s) {
	return (s + '').replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/'/g, '&#039;')
		.replace(/"/g, '&quot;')
		.replace(/\n/g, '<br />');
}
function stripHtmlEntities(text) {
	return text.replace(/&.*?;/g, '');
}
function capitaliseFirstLetter(str) {
	return str.charAt(0).toUpperCase() + str.slice(1);
}
function compareNumbers(a, b) { 
	return a - b;
}
function isInt(n) {
	return n % 1 === 0;
}
function debounce(func, wait) {
	var timeoutId;
	var args;
	var context;
	var timestamp; 
	var later = function() {
		var last = +new Date() - timestamp;
		if (last < wait && last > 0) {
			timeoutId = setTimeout(later, wait - last);
		}
		else {
			timeoutId = null;
			func.apply(context, args);
			if (!timeoutId) {
				context = args = null;
			}
		}
	};
	return function() {
		context = this;
		args = arguments;
		timestamp = +new Date();
		if (!timeoutId) {
			timeoutId = setTimeout(later, wait);
		}
	};
}
;;
var ambigDateOfMonthRegex = /^\s*\d{4}-\d\d$/;
var ambigTimeOrZoneRegex =
	/^\s*\d{4}-(?:(\d\d-\d\d)|(W\d\d$)|(W\d\d-\d)|(\d\d\d))((T| )(\d\d(:\d\d(:\d\d(\.\d+)?)?)?)?)?$/;
var newMomentProto = moment.fn; 
var oldMomentProto = $.extend({}, newMomentProto); 
var allowValueOptimization;
var setUTCValues; 
var setLocalValues; 
fc.moment = function() {
	return makeMoment(arguments);
};
fc.moment.utc = function() {
	var mom = makeMoment(arguments, true);
	if (mom.hasTime()) { 
		mom.utc();
	}
	return mom;
};
fc.moment.parseZone = function() {
	return makeMoment(arguments, true, true);
};
function makeMoment(args, parseAsUTC, parseZone) {
	var input = args[0];
	var isSingleString = args.length == 1 && typeof input === 'string';
	var isAmbigTime;
	var isAmbigZone;
	var ambigMatch;
	var mom;
	if (moment.isMoment(input)) {
		mom = moment.apply(null, args); 
		transferAmbigs(input, mom); 
	}
	else if (isNativeDate(input) || input === undefined) {
		mom = moment.apply(null, args); 
	}
	else { 
		isAmbigTime = false;
		isAmbigZone = false;
		if (isSingleString) {
			if (ambigDateOfMonthRegex.test(input)) {
				input += '-01';
				args = [ input ]; 
				isAmbigTime = true;
				isAmbigZone = true;
			}
			else if ((ambigMatch = ambigTimeOrZoneRegex.exec(input))) {
				isAmbigTime = !ambigMatch[5]; 
				isAmbigZone = true;
			}
		}
		else if ($.isArray(input)) {
			isAmbigZone = true;
		}
		if (parseAsUTC || isAmbigTime) {
			mom = moment.utc.apply(moment, args);
		}
		else {
			mom = moment.apply(null, args);
		}
		if (isAmbigTime) {
			mom._ambigTime = true;
			mom._ambigZone = true; 
		}
		else if (parseZone) { 
			if (isAmbigZone) {
				mom._ambigZone = true;
			}
			else if (isSingleString) {
				mom.zone(input); 
			}
		}
	}
	mom._fullCalendar = true; 
	return mom;
}
newMomentProto.clone = function() {
	var mom = oldMomentProto.clone.apply(this, arguments);
	transferAmbigs(this, mom);
	if (this._fullCalendar) {
		mom._fullCalendar = true;
	}
	return mom;
};
newMomentProto.time = function(time) {
	if (!this._fullCalendar) {
		return oldMomentProto.time.apply(this, arguments);
	}
	if (time == null) { 
		return moment.duration({
			hours: this.hours(),
			minutes: this.minutes(),
			seconds: this.seconds(),
			milliseconds: this.milliseconds()
		});
	}
	else { 
		this._ambigTime = false; 
		if (!moment.isDuration(time) && !moment.isMoment(time)) {
			time = moment.duration(time);
		}
		var dayHours = 0;
		if (moment.isDuration(time)) {
			dayHours = Math.floor(time.asDays()) * 24;
		}
		return this.hours(dayHours + time.hours())
			.minutes(time.minutes())
			.seconds(time.seconds())
			.milliseconds(time.milliseconds());
	}
};
newMomentProto.stripTime = function() {
	var a;
	if (!this._ambigTime) {
		a = this.toArray(); 
		this.utc(); 
		setUTCValues(this, a.slice(0, 3)); 
		this._ambigTime = true;
		this._ambigZone = true; 
	}
	return this; 
};
newMomentProto.hasTime = function() {
	return !this._ambigTime;
};
newMomentProto.stripZone = function() {
	var a, wasAmbigTime;
	if (!this._ambigZone) {
		a = this.toArray(); 
		wasAmbigTime = this._ambigTime;
		this.utc(); 
		setUTCValues(this, a); 
		if (wasAmbigTime) {
			this._ambigTime = true;
		}
		this._ambigZone = true;
	}
	return this; 
};
newMomentProto.hasZone = function() {
	return !this._ambigZone;
};
newMomentProto.zone = function(tzo) {
	if (tzo != null) { 
		this._ambigTime = false;
		this._ambigZone = false;
	}
	return oldMomentProto.zone.apply(this, arguments);
};
newMomentProto.local = function() {
	var a = this.toArray(); 
	var wasAmbigZone = this._ambigZone;
	oldMomentProto.local.apply(this, arguments); 
	if (wasAmbigZone) {
		setLocalValues(this, a);
	}
	return this; 
};
newMomentProto.format = function() {
	if (this._fullCalendar && arguments[0]) { 
		return formatDate(this, arguments[0]); 
	}
	if (this._ambigTime) {
		return oldMomentFormat(this, 'YYYY-MM-DD');
	}
	if (this._ambigZone) {
		return oldMomentFormat(this, 'YYYY-MM-DD[T]HH:mm:ss');
	}
	return oldMomentProto.format.apply(this, arguments);
};
newMomentProto.toISOString = function() {
	if (this._ambigTime) {
		return oldMomentFormat(this, 'YYYY-MM-DD');
	}
	if (this._ambigZone) {
		return oldMomentFormat(this, 'YYYY-MM-DD[T]HH:mm:ss');
	}
	return oldMomentProto.toISOString.apply(this, arguments);
};
newMomentProto.isWithin = function(start, end) {
	var a = commonlyAmbiguate([ this, start, end ]);
	return a[0] >= a[1] && a[0] < a[2];
};
newMomentProto.isSame = function(input, units) {
	var a;
	if (!this._fullCalendar) {
		return oldMomentProto.isSame.apply(this, arguments);
	}
	if (units) {
		a = commonlyAmbiguate([ this, input ], true); 
		return oldMomentProto.isSame.call(a[0], a[1], units);
	}
	else {
		input = fc.moment.parseZone(input); 
		return oldMomentProto.isSame.call(this, input) &&
			Boolean(this._ambigTime) === Boolean(input._ambigTime) &&
			Boolean(this._ambigZone) === Boolean(input._ambigZone);
	}
};
$.each([
	'isBefore',
	'isAfter'
], function(i, methodName) {
	newMomentProto[methodName] = function(input, units) {
		var a;
		if (!this._fullCalendar) {
			return oldMomentProto[methodName].apply(this, arguments);
		}
		a = commonlyAmbiguate([ this, input ]);
		return oldMomentProto[methodName].call(a[0], a[1], units);
	};
});
function commonlyAmbiguate(inputs, preserveTime) {
	var anyAmbigTime = false;
	var anyAmbigZone = false;
	var len = inputs.length;
	var moms = [];
	var i, mom;
	for (i = 0; i < len; i++) {
		mom = inputs[i];
		if (!moment.isMoment(mom)) {
			mom = fc.moment.parseZone(mom);
		}
		anyAmbigTime = anyAmbigTime || mom._ambigTime;
		anyAmbigZone = anyAmbigZone || mom._ambigZone;
		moms.push(mom);
	}
	for (i = 0; i < len; i++) {
		mom = moms[i];
		if (!preserveTime && anyAmbigTime && !mom._ambigTime) {
			moms[i] = mom.clone().stripTime();
		}
		else if (anyAmbigZone && !mom._ambigZone) {
			moms[i] = mom.clone().stripZone();
		}
	}
	return moms;
}
function transferAmbigs(src, dest) {
	if (src._ambigTime) {
		dest._ambigTime = true;
	}
	else if (dest._ambigTime) {
		dest._ambigTime = false;
	}
	if (src._ambigZone) {
		dest._ambigZone = true;
	}
	else if (dest._ambigZone) {
		dest._ambigZone = false;
	}
}
function setMomentValues(mom, a) {
	mom.year(a[0] || 0)
		.month(a[1] || 0)
		.date(a[2] || 0)
		.hours(a[3] || 0)
		.minutes(a[4] || 0)
		.seconds(a[5] || 0)
		.milliseconds(a[6] || 0);
}
allowValueOptimization = '_d' in moment() && 'updateOffset' in moment;
setUTCValues = allowValueOptimization ? function(mom, a) {
	mom._d.setTime(Date.UTC.apply(Date, a));
	moment.updateOffset(mom, false); 
} : setMomentValues;
setLocalValues = allowValueOptimization ? function(mom, a) {
	mom._d.setTime(+new Date( 
		a[0] || 0,
		a[1] || 0,
		a[2] || 0,
		a[3] || 0,
		a[4] || 0,
		a[5] || 0,
		a[6] || 0
	));
	moment.updateOffset(mom, false); 
} : setMomentValues;
;;
function oldMomentFormat(mom, formatStr) {
	return oldMomentProto.format.call(mom, formatStr); 
}
function formatDate(date, formatStr) {
	return formatDateWithChunks(date, getFormatStringChunks(formatStr));
}
function formatDateWithChunks(date, chunks) {
	var s = '';
	var i;
	for (i=0; i<chunks.length; i++) {
		s += formatDateWithChunk(date, chunks[i]);
	}
	return s;
}
var tokenOverrides = {
	t: function(date) { 
		return oldMomentFormat(date, 'a').charAt(0);
	},
	T: function(date) { 
		return oldMomentFormat(date, 'A').charAt(0);
	}
};
function formatDateWithChunk(date, chunk) {
	var token;
	var maybeStr;
	if (typeof chunk === 'string') { 
		return chunk;
	}
	else if ((token = chunk.token)) { 
		if (tokenOverrides[token]) {
			return tokenOverrides[token](date); 
		}
		return oldMomentFormat(date, token);
	}
	else if (chunk.maybe) { 
		maybeStr = formatDateWithChunks(date, chunk.maybe);
		if (maybeStr.match(/[1-9]/)) {
			return maybeStr;
		}
	}
	return '';
}
function formatRange(date1, date2, formatStr, separator, isRTL) {
	var localeData;
	date1 = fc.moment.parseZone(date1);
	date2 = fc.moment.parseZone(date2);
	localeData = (date1.localeData || date1.lang).call(date1); 
	formatStr = localeData.longDateFormat(formatStr) || formatStr;
	separator = separator || ' - ';
	return formatRangeWithChunks(
		date1,
		date2,
		getFormatStringChunks(formatStr),
		separator,
		isRTL
	);
}
fc.formatRange = formatRange; 
function formatRangeWithChunks(date1, date2, chunks, separator, isRTL) {
	var chunkStr; 
	var leftI;
	var leftStr = '';
	var rightI;
	var rightStr = '';
	var middleI;
	var middleStr1 = '';
	var middleStr2 = '';
	var middleStr = '';
	for (leftI=0; leftI<chunks.length; leftI++) {
		chunkStr = formatSimilarChunk(date1, date2, chunks[leftI]);
		if (chunkStr === false) {
			break;
		}
		leftStr += chunkStr;
	}
	for (rightI=chunks.length-1; rightI>leftI; rightI--) {
		chunkStr = formatSimilarChunk(date1, date2, chunks[rightI]);
		if (chunkStr === false) {
			break;
		}
		rightStr = chunkStr + rightStr;
	}
	for (middleI=leftI; middleI<=rightI; middleI++) {
		middleStr1 += formatDateWithChunk(date1, chunks[middleI]);
		middleStr2 += formatDateWithChunk(date2, chunks[middleI]);
	}
	if (middleStr1 || middleStr2) {
		if (isRTL) {
			middleStr = middleStr2 + separator + middleStr1;
		}
		else {
			middleStr = middleStr1 + separator + middleStr2;
		}
	}
	return leftStr + middleStr + rightStr;
}
var similarUnitMap = {
	Y: 'year',
	M: 'month',
	D: 'day', 
	d: 'day', 
	A: 'second', 
	a: 'second', 
	T: 'second', 
	t: 'second', 
	H: 'second', 
	h: 'second', 
	m: 'second', 
	s: 'second' 
};
function formatSimilarChunk(date1, date2, chunk) {
	var token;
	var unit;
	if (typeof chunk === 'string') { 
		return chunk;
	}
	else if ((token = chunk.token)) {
		unit = similarUnitMap[token.charAt(0)];
		if (unit && date1.isSame(date2, unit)) {
			return oldMomentFormat(date1, token); 
		}
	}
	return false; 
}
var formatStringChunkCache = {};
function getFormatStringChunks(formatStr) {
	if (formatStr in formatStringChunkCache) {
		return formatStringChunkCache[formatStr];
	}
	return (formatStringChunkCache[formatStr] = chunkFormatString(formatStr));
}
function chunkFormatString(formatStr) {
	var chunks = [];
	var chunker = /\[([^\]]*)\]|\(([^\)]*)\)|(LT|(\w)\4*o?)|([^\w\[\(]+)/g; 
	var match;
	while ((match = chunker.exec(formatStr))) {
		if (match[1]) { 
			chunks.push(match[1]);
		}
		else if (match[2]) { 
			chunks.push({ maybe: chunkFormatString(match[2]) });
		}
		else if (match[3]) { 
			chunks.push({ token: match[3] });
		}
		else if (match[5]) { 
			chunks.push(match[5]);
		}
	}
	return chunks;
}
;;
fc.Class = Class; 
function Class() { }
Class.extend = function(members) {
	var superClass = this;
	var subClass;
	members = members || {};
	if (hasOwnProp(members, 'constructor')) {
		subClass = members.constructor;
	}
	if (typeof subClass !== 'function') {
		subClass = members.constructor = function() {
			superClass.apply(this, arguments);
		};
	}
	subClass.prototype = createObject(superClass.prototype);
	copyOwnProps(members, subClass.prototype);
	copyOwnProps(superClass, subClass);
	return subClass;
};
Class.mixin = function(members) {
	copyOwnProps(members.prototype || members, this.prototype);
};
;;
var Popover = Class.extend({
	isHidden: true,
	options: null,
	el: null, 
	documentMousedownProxy: null, 
	margin: 10, 
	constructor: function(options) {
		this.options = options || {};
	},
	show: function() {
		if (this.isHidden) {
			if (!this.el) {
				this.render();
			}
			this.el.show();
			this.position();
			this.isHidden = false;
			this.trigger('show');
		}
	},
	hide: function() {
		if (!this.isHidden) {
			this.el.hide();
			this.isHidden = true;
			this.trigger('hide');
		}
	},
	render: function() {
		var _this = this;
		var options = this.options;
		this.el = $('<div class="fc-popover"/>')
			.addClass(options.className || '')
			.css({
				top: 0,
				left: 0
			})
			.append(options.content)
			.appendTo(options.parentEl);
		this.el.on('click', '.fc-close', function() {
			_this.hide();
		});
		if (options.autoHide) {
			$(document).on('mousedown', this.documentMousedownProxy = $.proxy(this, 'documentMousedown'));
		}
	},
	documentMousedown: function(ev) {
		if (this.el && !$(ev.target).closest(this.el).length) {
			this.hide();
		}
	},
	destroy: function() {
		this.hide();
		if (this.el) {
			this.el.remove();
			this.el = null;
		}
		$(document).off('mousedown', this.documentMousedownProxy);
	},
	position: function() {
		var options = this.options;
		var origin = this.el.offsetParent().offset();
		var width = this.el.outerWidth();
		var height = this.el.outerHeight();
		var windowEl = $(window);
		var viewportEl = getScrollParent(this.el);
		var viewportTop;
		var viewportLeft;
		var viewportOffset;
		var top; 
		var left; 
		top = options.top || 0;
		if (options.left !== undefined) {
			left = options.left;
		}
		else if (options.right !== undefined) {
			left = options.right - width; 
		}
		else {
			left = 0;
		}
		if (viewportEl.is(window) || viewportEl.is(document)) { 
			viewportEl = windowEl;
			viewportTop = 0; 
			viewportLeft = 0; 
		}
		else {
			viewportOffset = viewportEl.offset();
			viewportTop = viewportOffset.top;
			viewportLeft = viewportOffset.left;
		}
		viewportTop += windowEl.scrollTop();
		viewportLeft += windowEl.scrollLeft();
		if (options.viewportConstrain !== false) {
			top = Math.min(top, viewportTop + viewportEl.outerHeight() - height - this.margin);
			top = Math.max(top, viewportTop + this.margin);
			left = Math.min(left, viewportLeft + viewportEl.outerWidth() - width - this.margin);
			left = Math.max(left, viewportLeft + this.margin);
		}
		this.el.css({
			top: top - origin.top,
			left: left - origin.left
		});
	},
	trigger: function(name) {
		if (this.options[name]) {
			this.options[name].apply(this, Array.prototype.slice.call(arguments, 1));
		}
	}
});
;;
var GridCoordMap = Class.extend({
	grid: null, 
	rowCoords: null, 
	colCoords: null, 
	containerEl: null, 
	minX: null,
	maxX: null, 
	minY: null,
	maxY: null, 
	constructor: function(grid) {
		this.grid = grid;
	},
	build: function() {
		this.rowCoords = this.grid.computeRowCoords();
		this.colCoords = this.grid.computeColCoords();
		this.computeBounds();
	},
	clear: function() {
		this.rowCoords = null;
		this.colCoords = null;
	},
	getCell: function(x, y) {
		var rowCoords = this.rowCoords;
		var colCoords = this.colCoords;
		var hitRow = null;
		var hitCol = null;
		var i, coords;
		var cell;
		if (this.inBounds(x, y)) {
			for (i = 0; i < rowCoords.length; i++) {
				coords = rowCoords[i];
				if (y >= coords.top && y < coords.bottom) {
					hitRow = i;
					break;
				}
			}
			for (i = 0; i < colCoords.length; i++) {
				coords = colCoords[i];
				if (x >= coords.left && x < coords.right) {
					hitCol = i;
					break;
				}
			}
			if (hitRow !== null && hitCol !== null) {
				cell = this.grid.getCell(hitRow, hitCol);
				cell.grid = this.grid; 
				return cell;
			}
		}
		return null;
	},
	computeBounds: function() {
		var containerOffset;
		if (this.containerEl) {
			containerOffset = this.containerEl.offset();
			this.minX = containerOffset.left;
			this.maxX = containerOffset.left + this.containerEl.outerWidth();
			this.minY = containerOffset.top;
			this.maxY = containerOffset.top + this.containerEl.outerHeight();
		}
	},
	inBounds: function(x, y) {
		if (this.containerEl) {
			return x >= this.minX && x < this.maxX && y >= this.minY && y < this.maxY;
		}
		return true;
	}
});
var ComboCoordMap = Class.extend({
	coordMaps: null, 
	constructor: function(coordMaps) {
		this.coordMaps = coordMaps;
	},
	build: function() {
		var coordMaps = this.coordMaps;
		var i;
		for (i = 0; i < coordMaps.length; i++) {
			coordMaps[i].build();
		}
	},
	getCell: function(x, y) {
		var coordMaps = this.coordMaps;
		var cell = null;
		var i;
		for (i = 0; i < coordMaps.length && !cell; i++) {
			cell = coordMaps[i].getCell(x, y);
		}
		return cell;
	},
	clear: function() {
		var coordMaps = this.coordMaps;
		var i;
		for (i = 0; i < coordMaps.length; i++) {
			coordMaps[i].clear();
		}
	}
});
;;
var DragListener = Class.extend({
	coordMap: null,
	options: null,
	isListening: false,
	isDragging: false,
	origCell: null,
	cell: null,
	mouseX0: null,
	mouseY0: null,
	mousemoveProxy: null,
	mouseupProxy: null,
	scrollEl: null,
	scrollBounds: null, 
	scrollTopVel: null, 
	scrollLeftVel: null, 
	scrollIntervalId: null, 
	scrollHandlerProxy: null, 
	scrollSensitivity: 30, 
	scrollSpeed: 200, 
	scrollIntervalMs: 50, 
	constructor: function(coordMap, options) {
		this.coordMap = coordMap;
		this.options = options || {};
	},
	mousedown: function(ev) {
		if (isPrimaryMouseButton(ev)) {
			ev.preventDefault(); 
			this.startListening(ev);
			if (!this.options.distance) {
				this.startDrag(ev);
			}
		}
	},
	startListening: function(ev) {
		var scrollParent;
		var cell;
		if (!this.isListening) {
			if (ev && this.options.scroll) {
				scrollParent = getScrollParent($(ev.target));
				if (!scrollParent.is(window) && !scrollParent.is(document)) {
					this.scrollEl = scrollParent;
					this.scrollHandlerProxy = debounce($.proxy(this, 'scrollHandler'), 100);
					this.scrollEl.on('scroll', this.scrollHandlerProxy);
				}
			}
			this.computeCoords(); 
			if (ev) {
				cell = this.getCell(ev);
				this.origCell = cell;
				this.mouseX0 = ev.pageX;
				this.mouseY0 = ev.pageY;
			}
			$(document)
				.on('mousemove', this.mousemoveProxy = $.proxy(this, 'mousemove'))
				.on('mouseup', this.mouseupProxy = $.proxy(this, 'mouseup'))
				.on('selectstart', this.preventDefault); 
			this.isListening = true;
			this.trigger('listenStart', ev);
		}
	},
	computeCoords: function() {
		this.coordMap.build();
		this.computeScrollBounds();
	},
	mousemove: function(ev) {
		var minDistance;
		var distanceSq; 
		if (!this.isDragging) { 
			minDistance = this.options.distance || 1;
			distanceSq = Math.pow(ev.pageX - this.mouseX0, 2) + Math.pow(ev.pageY - this.mouseY0, 2);
			if (distanceSq >= minDistance * minDistance) { 
				this.startDrag(ev);
			}
		}
		if (this.isDragging) {
			this.drag(ev); 
		}
	},
	startDrag: function(ev) {
		var cell;
		if (!this.isListening) { 
			this.startListening();
		}
		if (!this.isDragging) {
			this.isDragging = true;
			this.trigger('dragStart', ev);
			cell = this.getCell(ev); 
			if (cell) {
				this.cellOver(cell);
			}
		}
	},
	drag: function(ev) {
		var cell;
		if (this.isDragging) {
			cell = this.getCell(ev);
			if (!isCellsEqual(cell, this.cell)) { 
				if (this.cell) {
					this.cellOut();
				}
				if (cell) {
					this.cellOver(cell);
				}
			}
			this.dragScroll(ev); 
		}
	},
	cellOver: function(cell) {
		this.cell = cell;
		this.trigger('cellOver', cell, isCellsEqual(cell, this.origCell));
	},
	cellOut: function() {
		if (this.cell) {
			this.trigger('cellOut', this.cell);
			this.cell = null;
		}
	},
	mouseup: function(ev) {
		this.stopDrag(ev);
		this.stopListening(ev);
	},
	stopDrag: function(ev) {
		if (this.isDragging) {
			this.stopScrolling();
			this.trigger('dragStop', ev);
			this.isDragging = false;
		}
	},
	stopListening: function(ev) {
		if (this.isListening) {
			if (this.scrollEl) {
				this.scrollEl.off('scroll', this.scrollHandlerProxy);
				this.scrollHandlerProxy = null;
			}
			$(document)
				.off('mousemove', this.mousemoveProxy)
				.off('mouseup', this.mouseupProxy)
				.off('selectstart', this.preventDefault);
			this.mousemoveProxy = null;
			this.mouseupProxy = null;
			this.isListening = false;
			this.trigger('listenStop', ev);
			this.origCell = this.cell = null;
			this.coordMap.clear();
		}
	},
	getCell: function(ev) {
		return this.coordMap.getCell(ev.pageX, ev.pageY);
	},
	trigger: function(name) {
		if (this.options[name]) {
			this.options[name].apply(this, Array.prototype.slice.call(arguments, 1));
		}
	},
	preventDefault: function(ev) {
		ev.preventDefault();
	},
	computeScrollBounds: function() {
		var el = this.scrollEl;
		var offset;
		if (el) {
			offset = el.offset();
			this.scrollBounds = {
				top: offset.top,
				left: offset.left,
				bottom: offset.top + el.outerHeight(),
				right: offset.left + el.outerWidth()
			};
		}
	},
	dragScroll: function(ev) {
		var sensitivity = this.scrollSensitivity;
		var bounds = this.scrollBounds;
		var topCloseness, bottomCloseness;
		var leftCloseness, rightCloseness;
		var topVel = 0;
		var leftVel = 0;
		if (bounds) { 
			topCloseness = (sensitivity - (ev.pageY - bounds.top)) / sensitivity;
			bottomCloseness = (sensitivity - (bounds.bottom - ev.pageY)) / sensitivity;
			leftCloseness = (sensitivity - (ev.pageX - bounds.left)) / sensitivity;
			rightCloseness = (sensitivity - (bounds.right - ev.pageX)) / sensitivity;
			if (topCloseness >= 0 && topCloseness <= 1) {
				topVel = topCloseness * this.scrollSpeed * -1; 
			}
			else if (bottomCloseness >= 0 && bottomCloseness <= 1) {
				topVel = bottomCloseness * this.scrollSpeed;
			}
			if (leftCloseness >= 0 && leftCloseness <= 1) {
				leftVel = leftCloseness * this.scrollSpeed * -1; 
			}
			else if (rightCloseness >= 0 && rightCloseness <= 1) {
				leftVel = rightCloseness * this.scrollSpeed;
			}
		}
		this.setScrollVel(topVel, leftVel);
	},
	setScrollVel: function(topVel, leftVel) {
		this.scrollTopVel = topVel;
		this.scrollLeftVel = leftVel;
		this.constrainScrollVel(); 
		if ((this.scrollTopVel || this.scrollLeftVel) && !this.scrollIntervalId) {
			this.scrollIntervalId = setInterval(
				$.proxy(this, 'scrollIntervalFunc'), 
				this.scrollIntervalMs
			);
		}
	},
	constrainScrollVel: function() {
		var el = this.scrollEl;
		if (this.scrollTopVel < 0) { 
			if (el.scrollTop() <= 0) { 
				this.scrollTopVel = 0;
			}
		}
		else if (this.scrollTopVel > 0) { 
			if (el.scrollTop() + el[0].clientHeight >= el[0].scrollHeight) { 
				this.scrollTopVel = 0;
			}
		}
		if (this.scrollLeftVel < 0) { 
			if (el.scrollLeft() <= 0) { 
				this.scrollLeftVel = 0;
			}
		}
		else if (this.scrollLeftVel > 0) { 
			if (el.scrollLeft() + el[0].clientWidth >= el[0].scrollWidth) { 
				this.scrollLeftVel = 0;
			}
		}
	},
	scrollIntervalFunc: function() {
		var el = this.scrollEl;
		var frac = this.scrollIntervalMs / 1000; 
		if (this.scrollTopVel) {
			el.scrollTop(el.scrollTop() + this.scrollTopVel * frac);
		}
		if (this.scrollLeftVel) {
			el.scrollLeft(el.scrollLeft() + this.scrollLeftVel * frac);
		}
		this.constrainScrollVel(); 
		if (!this.scrollTopVel && !this.scrollLeftVel) {
			this.stopScrolling();
		}
	},
	stopScrolling: function() {
		if (this.scrollIntervalId) {
			clearInterval(this.scrollIntervalId);
			this.scrollIntervalId = null;
			this.computeCoords();
		}
	},
	scrollHandler: function() {
		if (!this.scrollIntervalId) {
			this.computeCoords();
		}
	}
});
function isCellsEqual(cell1, cell2) {
	if (!cell1 && !cell2) {
		return true;
	}
	if (cell1 && cell2) {
		return cell1.grid === cell2.grid &&
			cell1.row === cell2.row &&
			cell1.col === cell2.col;
	}
	return false;
}
;;
var MouseFollower = Class.extend({
	options: null,
	sourceEl: null, 
	el: null, 
	parentEl: null, 
	top0: null,
	left0: null,
	mouseY0: null,
	mouseX0: null,
	topDelta: null,
	leftDelta: null,
	mousemoveProxy: null, 
	isFollowing: false,
	isHidden: false,
	isAnimating: false, 
	constructor: function(sourceEl, options) {
		this.options = options = options || {};
		this.sourceEl = sourceEl;
		this.parentEl = options.parentEl ? $(options.parentEl) : sourceEl.parent(); 
	},
	start: function(ev) {
		if (!this.isFollowing) {
			this.isFollowing = true;
			this.mouseY0 = ev.pageY;
			this.mouseX0 = ev.pageX;
			this.topDelta = 0;
			this.leftDelta = 0;
			if (!this.isHidden) {
				this.updatePosition();
			}
			$(document).on('mousemove', this.mousemoveProxy = $.proxy(this, 'mousemove'));
		}
	},
	stop: function(shouldRevert, callback) {
		var _this = this;
		var revertDuration = this.options.revertDuration;
		function complete() {
			this.isAnimating = false;
			_this.destroyEl();
			this.top0 = this.left0 = null; 
			if (callback) {
				callback();
			}
		}
		if (this.isFollowing && !this.isAnimating) { 
			this.isFollowing = false;
			$(document).off('mousemove', this.mousemoveProxy);
			if (shouldRevert && revertDuration && !this.isHidden) { 
				this.isAnimating = true;
				this.el.animate({
					top: this.top0,
					left: this.left0
				}, {
					duration: revertDuration,
					complete: complete
				});
			}
			else {
				complete();
			}
		}
	},
	getEl: function() {
		var el = this.el;
		if (!el) {
			this.sourceEl.width(); 
			el = this.el = this.sourceEl.clone()
				.css({
					position: 'absolute',
					visibility: '', 
					display: this.isHidden ? 'none' : '', 
					margin: 0,
					right: 'auto', 
					bottom: 'auto', 
					width: this.sourceEl.width(), 
					height: this.sourceEl.height(), 
					opacity: this.options.opacity || '',
					zIndex: this.options.zIndex
				})
				.appendTo(this.parentEl);
		}
		return el;
	},
	destroyEl: function() {
		if (this.el) {
			this.el.remove();
			this.el = null;
		}
	},
	updatePosition: function() {
		var sourceOffset;
		var origin;
		this.getEl(); 
		if (this.top0 === null) {
			this.sourceEl.width(); 
			sourceOffset = this.sourceEl.offset();
			origin = this.el.offsetParent().offset();
			this.top0 = sourceOffset.top - origin.top;
			this.left0 = sourceOffset.left - origin.left;
		}
		this.el.css({
			top: this.top0 + this.topDelta,
			left: this.left0 + this.leftDelta
		});
	},
	mousemove: function(ev) {
		this.topDelta = ev.pageY - this.mouseY0;
		this.leftDelta = ev.pageX - this.mouseX0;
		if (!this.isHidden) {
			this.updatePosition();
		}
	},
	hide: function() {
		if (!this.isHidden) {
			this.isHidden = true;
			if (this.el) {
				this.el.hide();
			}
		}
	},
	show: function() {
		if (this.isHidden) {
			this.isHidden = false;
			this.updatePosition();
			this.getEl().show();
		}
	}
});
;;
var RowRenderer = Class.extend({
	view: null, 
	isRTL: null, 
	cellHtml: '<td/>', 
	constructor: function(view) {
		this.view = view;
		this.isRTL = view.opt('isRTL');
	},
	rowHtml: function(rowType, row) {
		var renderCell = this.getHtmlRenderer('cell', rowType);
		var rowCellHtml = '';
		var col;
		var cell;
		row = row || 0;
		for (col = 0; col < this.colCnt; col++) {
			cell = this.getCell(row, col);
			rowCellHtml += renderCell(cell);
		}
		rowCellHtml = this.bookendCells(rowCellHtml, rowType, row); 
		return '<tr>' + rowCellHtml + '</tr>';
	},
	bookendCells: function(cells, rowType, row) {
		var intro = this.getHtmlRenderer('intro', rowType)(row || 0);
		var outro = this.getHtmlRenderer('outro', rowType)(row || 0);
		var prependHtml = this.isRTL ? outro : intro;
		var appendHtml = this.isRTL ? intro : outro;
		if (typeof cells === 'string') {
			return prependHtml + cells + appendHtml;
		}
		else { 
			return cells.prepend(prependHtml).append(appendHtml);
		}
	},
	getHtmlRenderer: function(rendererName, rowType) {
		var view = this.view;
		var generalName; 
		var specificName; 
		var provider; 
		var renderer;
		generalName = rendererName + 'Html';
		if (rowType) {
			specificName = rowType + capitaliseFirstLetter(rendererName) + 'Html';
		}
		if (specificName && (renderer = view[specificName])) {
			provider = view;
		}
		else if (specificName && (renderer = this[specificName])) {
			provider = this;
		}
		else if ((renderer = view[generalName])) {
			provider = view;
		}
		else if ((renderer = this[generalName])) {
			provider = this;
		}
		if (typeof renderer === 'function') {
			return function() {
				return renderer.apply(provider, arguments) || ''; 
			};
		}
		return function() {
			return renderer || '';
		};
	}
});
;;
var Grid = fc.Grid = RowRenderer.extend({
	start: null, 
	end: null, 
	rowCnt: 0, 
	colCnt: 0, 
	rowData: null, 
	colData: null, 
	el: null, 
	coordMap: null, 
	elsByFill: null, 
	documentDragStartProxy: null, 
	colHeadFormat: null, 
	eventTimeFormat: null,
	displayEventEnd: null,
	constructor: function() {
		RowRenderer.apply(this, arguments); 
		this.coordMap = new GridCoordMap(this);
		this.elsByFill = {};
		this.documentDragStartProxy = $.proxy(this, 'documentDragStart');
	},
	render: function() {
		this.bindHandlers();
	},
	destroy: function() {
		this.unbindHandlers();
	},
	computeColHeadFormat: function() {
	},
	computeEventTimeFormat: function() {
		return this.view.opt('smallTimeFormat');
	},
	computeDisplayEventEnd: function() {
		return false;
	},
	setRange: function(range) {
		var view = this.view;
		this.start = range.start.clone();
		this.end = range.end.clone();
		this.rowData = [];
		this.colData = [];
		this.updateCells();
		this.colHeadFormat = view.opt('columnFormat') || this.computeColHeadFormat();
		this.eventTimeFormat = view.opt('timeFormat') || this.computeEventTimeFormat();
		this.displayEventEnd = view.opt('displayEventEnd');
		if (this.displayEventEnd == null) {
			this.displayEventEnd = this.computeDisplayEventEnd();
		}
	},
	updateCells: function() {
	},
	rangeToSegs: function(range) {
	},
	getCell: function(row, col) {
		var cell;
		if (col == null) {
			if (typeof row === 'number') { 
				col = row % this.colCnt;
				row = Math.floor(row / this.colCnt);
			}
			else { 
				col = row.col;
				row = row.row;
			}
		}
		cell = { row: row, col: col };
		$.extend(cell, this.getRowData(row), this.getColData(col));
		$.extend(cell, this.computeCellRange(cell));
		return cell;
	},
	computeCellRange: function(cell) {
	},
	getRowData: function(row) {
		return this.rowData[row] || {};
	},
	getColData: function(col) {
		return this.colData[col] || {};
	},
	getRowEl: function(row) {
	},
	getColEl: function(col) {
	},
	getCellDayEl: function(cell) {
		return this.getColEl(cell.col) || this.getRowEl(cell.row);
	},
	computeRowCoords: function() {
		var items = [];
		var i, el;
		var item;
		for (i = 0; i < this.rowCnt; i++) {
			el = this.getRowEl(i);
			item = {
				top: el.offset().top
			};
			if (i > 0) {
				items[i - 1].bottom = item.top;
			}
			items.push(item);
		}
		item.bottom = item.top + el.outerHeight();
		return items;
	},
	computeColCoords: function() {
		var items = [];
		var i, el;
		var item;
		for (i = 0; i < this.colCnt; i++) {
			el = this.getColEl(i);
			item = {
				left: el.offset().left
			};
			if (i > 0) {
				items[i - 1].right = item.left;
			}
			items.push(item);
		}
		item.right = item.left + el.outerWidth();
		return items;
	},
	bindHandlers: function() {
		var _this = this;
		this.el.on('mousedown', function(ev) {
			if (
				!$(ev.target).is('.fc-event-container *, .fc-more') && 
				!$(ev.target).closest('.fc-popover').length 
			) {
				_this.dayMousedown(ev);
			}
		});
		this.bindSegHandlers();
		$(document).on('dragstart', this.documentDragStartProxy); 
	},
	unbindHandlers: function() {
		$(document).off('dragstart', this.documentDragStartProxy); 
	},
	dayMousedown: function(ev) {
		var _this = this;
		var view = this.view;
		var isSelectable = view.opt('selectable');
		var dayClickCell; 
		var selectionRange; 
		var dragListener = new DragListener(this.coordMap, {
			scroll: view.opt('dragScroll'),
			dragStart: function() {
				view.unselect(); 
			},
			cellOver: function(cell, isOrig) {
				var origCell = dragListener.origCell;
				if (origCell) { 
					dayClickCell = isOrig ? cell : null; 
					if (isSelectable) {
						selectionRange = _this.computeSelection(origCell, cell);
						if (selectionRange) {
							_this.renderSelection(selectionRange);
						}
						else {
							disableCursor();
						}
					}
				}
			},
			cellOut: function(cell) {
				dayClickCell = null;
				selectionRange = null;
				_this.destroySelection();
				enableCursor();
			},
			listenStop: function(ev) {
				if (dayClickCell) {
					view.trigger('dayClick', _this.getCellDayEl(dayClickCell), dayClickCell.start, ev);
				}
				if (selectionRange) {
					view.reportSelection(selectionRange, ev);
				}
				enableCursor();
			}
		});
		dragListener.mousedown(ev); 
	},
	renderRangeHelper: function(range, sourceSeg) {
		var fakeEvent;
		fakeEvent = sourceSeg ? createObject(sourceSeg.event) : {}; 
		fakeEvent.start = range.start.clone();
		fakeEvent.end = range.end ? range.end.clone() : null;
		fakeEvent.allDay = null; 
		this.view.calendar.normalizeEventDateProps(fakeEvent);
		fakeEvent.className = (fakeEvent.className || []).concat('fc-helper');
		if (!sourceSeg) {
			fakeEvent.editable = false;
		}
		this.renderHelper(fakeEvent, sourceSeg); 
	},
	renderHelper: function(event, sourceSeg) {
	},
	destroyHelper: function() {
	},
	renderSelection: function(range) {
		this.renderHighlight(range);
	},
	destroySelection: function() {
		this.destroyHighlight();
	},
	computeSelection: function(firstCell, lastCell) {
		var dates = [
			firstCell.start,
			firstCell.end,
			lastCell.start,
			lastCell.end
		];
		var range;
		dates.sort(compareNumbers); 
		range = {
			start: dates[0].clone(),
			end: dates[3].clone()
		};
		if (!this.view.calendar.isSelectionRangeAllowed(range)) {
			return null;
		}
		return range;
	},
	renderHighlight: function(range) {
		this.renderFill('highlight', this.rangeToSegs(range));
	},
	destroyHighlight: function() {
		this.destroyFill('highlight');
	},
	highlightSegClasses: function() {
		return [ 'fc-highlight' ];
	},
	renderFill: function(type, segs) {
	},
	destroyFill: function(type) {
		var el = this.elsByFill[type];
		if (el) {
			el.remove();
			delete this.elsByFill[type];
		}
	},
	renderFillSegEls: function(type, segs) {
		var _this = this;
		var segElMethod = this[type + 'SegEl'];
		var html = '';
		var renderedSegs = [];
		var i;
		if (segs.length) {
			for (i = 0; i < segs.length; i++) {
				html += this.fillSegHtml(type, segs[i]);
			}
			$(html).each(function(i, node) {
				var seg = segs[i];
				var el = $(node);
				if (segElMethod) {
					el = segElMethod.call(_this, seg, el);
				}
				if (el) { 
					el = $(el); 
					if (el.is(_this.fillSegTag)) {
						seg.el = el;
						renderedSegs.push(seg);
					}
				}
			});
		}
		return renderedSegs;
	},
	fillSegTag: 'div', 
	fillSegHtml: function(type, seg) {
		var classesMethod = this[type + 'SegClasses']; 
		var stylesMethod = this[type + 'SegStyles']; 
		var classes = classesMethod ? classesMethod.call(this, seg) : [];
		var styles = stylesMethod ? stylesMethod.call(this, seg) : ''; 
		return '<' + this.fillSegTag +
			(classes.length ? ' class="' + classes.join(' ') + '"' : '') +
			(styles ? ' style="' + styles + '"' : '') +
			' />';
	},
	headHtml: function() {
		return '' +
			'<div class="fc-row ' + this.view.widgetHeaderClass + '">' +
				'<table>' +
					'<thead>' +
						this.rowHtml('head') + 
					'</thead>' +
				'</table>' +
			'</div>';
	},
	headCellHtml: function(cell) {
		var view = this.view;
		var date = cell.start;
		return '' +
			'<th class="fc-day-header ' + view.widgetHeaderClass + ' fc-' + dayIDs[date.day()] + '">' +
				htmlEscape(date.format(this.colHeadFormat)) +
			'</th>';
	},
	bgCellHtml: function(cell) {
		var view = this.view;
		var date = cell.start;
		var classes = this.getDayClasses(date);
		classes.unshift('fc-day', view.widgetContentClass);
		return '<td class="' + classes.join(' ') + '"' +
			' data-date="' + date.format('YYYY-MM-DD') + '"' + 
			'></td>';
	},
	getDayClasses: function(date) {
		var view = this.view;
		var today = view.calendar.getNow().stripTime();
		var classes = [ 'fc-' + dayIDs[date.day()] ];
		if (
			view.name === 'month' &&
			date.month() != view.intervalStart.month()
		) {
			classes.push('fc-other-month');
		}
		if (date.isSame(today, 'day')) {
			classes.push(
				'fc-today',
				view.highlightStateClass
			);
		}
		else if (date < today) {
			classes.push('fc-past');
		}
		else {
			classes.push('fc-future');
		}
		return classes;
	}
});
;;
Grid.mixin({
	mousedOverSeg: null, 
	isDraggingSeg: false, 
	isResizingSeg: false, 
	segs: null, 
	renderEvents: function(events) {
		var segs = this.eventsToSegs(events);
		var bgSegs = [];
		var fgSegs = [];
		var i, seg;
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			if (isBgEvent(seg.event)) {
				bgSegs.push(seg);
			}
			else {
				fgSegs.push(seg);
			}
		}
		bgSegs = this.renderBgSegs(bgSegs) || bgSegs;
		fgSegs = this.renderFgSegs(fgSegs) || fgSegs;
		this.segs = bgSegs.concat(fgSegs);
	},
	destroyEvents: function() {
		this.triggerSegMouseout(); 
		this.destroyFgSegs();
		this.destroyBgSegs();
		this.segs = null;
	},
	getEventSegs: function() {
		return this.segs || [];
	},
	renderFgSegs: function(segs) {
	},
	destroyFgSegs: function() {
	},
	renderFgSegEls: function(segs, disableResizing) {
		var view = this.view;
		var html = '';
		var renderedSegs = [];
		var i;
		if (segs.length) { 
			for (i = 0; i < segs.length; i++) {
				html += this.fgSegHtml(segs[i], disableResizing);
			}
			$(html).each(function(i, node) {
				var seg = segs[i];
				var el = view.resolveEventEl(seg.event, $(node));
				if (el) {
					el.data('fc-seg', seg); 
					seg.el = el;
					renderedSegs.push(seg);
				}
			});
		}
		return renderedSegs;
	},
	fgSegHtml: function(seg, disableResizing) {
	},
	renderBgSegs: function(segs) {
		return this.renderFill('bgEvent', segs);
	},
	destroyBgSegs: function() {
		this.destroyFill('bgEvent');
	},
	bgEventSegEl: function(seg, el) {
		return this.view.resolveEventEl(seg.event, el); 
	},
	bgEventSegClasses: function(seg) {
		var event = seg.event;
		var source = event.source || {};
		return [ 'fc-bgevent' ].concat(
			event.className,
			source.className || []
		);
	},
	bgEventSegStyles: function(seg) {
		var view = this.view;
		var event = seg.event;
		var source = event.source || {};
		var eventColor = event.color;
		var sourceColor = source.color;
		var optionColor = view.opt('eventColor');
		var backgroundColor =
			event.backgroundColor ||
			eventColor ||
			source.backgroundColor ||
			sourceColor ||
			view.opt('eventBackgroundColor') ||
			optionColor;
		if (backgroundColor) {
			return 'background-color:' + backgroundColor;
		}
		return '';
	},
	businessHoursSegClasses: function(seg) {
		return [ 'fc-nonbusiness', 'fc-bgevent' ];
	},
	bindSegHandlers: function() {
		var _this = this;
		var view = this.view;
		$.each(
			{
				mouseenter: function(seg, ev) {
					_this.triggerSegMouseover(seg, ev);
				},
				mouseleave: function(seg, ev) {
					_this.triggerSegMouseout(seg, ev);
				},
				click: function(seg, ev) {
					return view.trigger('eventClick', this, seg.event, ev); 
				},
				mousedown: function(seg, ev) {
					if ($(ev.target).is('.fc-resizer') && view.isEventResizable(seg.event)) {
						_this.segResizeMousedown(seg, ev);
					}
					else if (view.isEventDraggable(seg.event)) {
						_this.segDragMousedown(seg, ev);
					}
				}
			},
			function(name, func) {
				_this.el.on(name, '.fc-event-container > *', function(ev) {
					var seg = $(this).data('fc-seg'); 
					if (seg && !_this.isDraggingSeg && !_this.isResizingSeg) {
						return func.call(this, seg, ev); 
					}
				});
			}
		);
	},
	triggerSegMouseover: function(seg, ev) {
		if (!this.mousedOverSeg) {
			this.mousedOverSeg = seg;
			this.view.trigger('eventMouseover', seg.el[0], seg.event, ev);
		}
	},
	triggerSegMouseout: function(seg, ev) {
		ev = ev || {}; 
		if (this.mousedOverSeg) {
			seg = seg || this.mousedOverSeg; 
			this.mousedOverSeg = null;
			this.view.trigger('eventMouseout', seg.el[0], seg.event, ev);
		}
	},
	segDragMousedown: function(seg, ev) {
		var _this = this;
		var view = this.view;
		var el = seg.el;
		var event = seg.event;
		var dropLocation;
		var mouseFollower = new MouseFollower(seg.el, {
			parentEl: view.el,
			opacity: view.opt('dragOpacity'),
			revertDuration: view.opt('dragRevertDuration'),
			zIndex: 2 
		});
		var dragListener = new DragListener(view.coordMap, {
			distance: 5,
			scroll: view.opt('dragScroll'),
			listenStart: function(ev) {
				mouseFollower.hide(); 
				mouseFollower.start(ev);
			},
			dragStart: function(ev) {
				_this.triggerSegMouseout(seg, ev); 
				_this.isDraggingSeg = true;
				view.hideEvent(event); 
				view.trigger('eventDragStart', el[0], event, ev, {}); 
			},
			cellOver: function(cell, isOrig) {
				var origCell = seg.cell || dragListener.origCell; 
				dropLocation = _this.computeEventDrop(origCell, cell, event);
				if (dropLocation) {
					if (view.renderDrag(dropLocation, seg)) { 
						mouseFollower.hide(); 
					}
					else {
						mouseFollower.show();
					}
					if (isOrig) {
						dropLocation = null; 
					}
				}
				else {
					mouseFollower.show();
					disableCursor();
				}
			},
			cellOut: function() { 
				dropLocation = null;
				view.destroyDrag(); 
				mouseFollower.show(); 
				enableCursor();
			},
			dragStop: function(ev) {
				mouseFollower.stop(!dropLocation, function() {
					_this.isDraggingSeg = false;
					view.destroyDrag();
					view.showEvent(event);
					view.trigger('eventDragStop', el[0], event, ev, {}); 
					if (dropLocation) {
						view.reportEventDrop(event, dropLocation, el, ev);
					}
				});
				enableCursor();
			},
			listenStop: function() {
				mouseFollower.stop(); 
			}
		});
		dragListener.mousedown(ev); 
	},
	computeEventDrop: function(startCell, endCell, event) {
		var dragStart = startCell.start;
		var dragEnd = endCell.start;
		var delta;
		var newStart;
		var newEnd;
		var newAllDay;
		var dropLocation;
		if (dragStart.hasTime() === dragEnd.hasTime()) {
			delta = diffDayTime(dragEnd, dragStart);
			newStart = event.start.clone().add(delta);
			if (event.end === null) { 
				newEnd = null;
			}
			else {
				newEnd = event.end.clone().add(delta);
			}
			newAllDay = event.allDay; 
		}
		else {
			newStart = dragEnd.clone();
			newEnd = null; 
			newAllDay = !dragEnd.hasTime();
		}
		dropLocation = {
			start: newStart,
			end: newEnd,
			allDay: newAllDay
		};
		if (!this.view.calendar.isEventRangeAllowed(dropLocation, event)) {
			return null;
		}
		return dropLocation;
	},
	documentDragStart: function(ev, ui) {
		var view = this.view;
		var el;
		var accept;
		if (view.opt('droppable')) { 
			el = $(ev.target);
			accept = view.opt('dropAccept');
			if ($.isFunction(accept) ? accept.call(el[0], el) : el.is(accept)) {
				this.startExternalDrag(el, ev, ui);
			}
		}
	},
	startExternalDrag: function(el, ev, ui) {
		var _this = this;
		var meta = getDraggedElMeta(el); 
		var dragListener;
		var dropLocation; 
		dragListener = new DragListener(this.coordMap, {
			cellOver: function(cell) {
				dropLocation = _this.computeExternalDrop(cell, meta);
				if (dropLocation) {
					_this.renderDrag(dropLocation); 
				}
				else { 
					disableCursor();
				}
			},
			cellOut: function() {
				dropLocation = null; 
				_this.destroyDrag();
				enableCursor();
			}
		});
		$(document).one('dragstop', function(ev, ui) {
			_this.destroyDrag();
			enableCursor();
			if (dropLocation) { 
				_this.view.reportExternalDrop(meta, dropLocation, el, ev, ui);
			}
		});
		dragListener.startDrag(ev); 
	},
	computeExternalDrop: function(cell, meta) {
		var dropLocation = {
			start: cell.start.clone(),
			end: null
		};
		if (meta.startTime && !dropLocation.start.hasTime()) {
			dropLocation.start.time(meta.startTime);
		}
		if (meta.duration) {
			dropLocation.end = dropLocation.start.clone().add(meta.duration);
		}
		if (!this.view.calendar.isExternalDropRangeAllowed(dropLocation, meta.eventProps)) {
			return null;
		}
		return dropLocation;
	},
	renderDrag: function(dropLocation, seg) {
	},
	destroyDrag: function() {
	},
	segResizeMousedown: function(seg, ev) {
		var _this = this;
		var view = this.view;
		var calendar = view.calendar;
		var el = seg.el;
		var event = seg.event;
		var start = event.start;
		var oldEnd = calendar.getEventEnd(event);
		var newEnd; 
		var dragListener;
		function destroy() { 
			_this.destroyEventResize();
			view.showEvent(event);
			enableCursor();
		}
		dragListener = new DragListener(this.coordMap, {
			distance: 5,
			scroll: view.opt('dragScroll'),
			dragStart: function(ev) {
				_this.triggerSegMouseout(seg, ev); 
				_this.isResizingSeg = true;
				view.trigger('eventResizeStart', el[0], event, ev, {}); 
			},
			cellOver: function(cell) {
				newEnd = cell.end;
				if (!newEnd.isAfter(start)) { 
					newEnd = start.clone().add( 
						diffDayTime(cell.end, cell.start) 
					);
				}
				if (newEnd.isSame(oldEnd)) {
					newEnd = null;
				}
				else if (!calendar.isEventRangeAllowed({ start: start, end: newEnd }, event)) {
					newEnd = null;
					disableCursor();
				}
				else {
					_this.renderEventResize({ start: start, end: newEnd }, seg);
					view.hideEvent(event);
				}
			},
			cellOut: function() { 
				newEnd = null;
				destroy();
			},
			dragStop: function(ev) {
				_this.isResizingSeg = false;
				destroy();
				view.trigger('eventResizeStop', el[0], event, ev, {}); 
				if (newEnd) { 
					view.reportEventResize(event, newEnd, el, ev);
				}
			}
		});
		dragListener.mousedown(ev); 
	},
	renderEventResize: function(range, seg) {
	},
	destroyEventResize: function() {
	},
	getEventTimeText: function(range, formatStr) {
		formatStr = formatStr || this.eventTimeFormat;
		if (range.end && this.displayEventEnd) {
			return this.view.formatRange(range, formatStr);
		}
		else {
			return range.start.format(formatStr);
		}
	},
	getSegClasses: function(seg, isDraggable, isResizable) {
		var event = seg.event;
		var classes = [
			'fc-event',
			seg.isStart ? 'fc-start' : 'fc-not-start',
			seg.isEnd ? 'fc-end' : 'fc-not-end'
		].concat(
			event.className,
			event.source ? event.source.className : []
		);
		if (isDraggable) {
			classes.push('fc-draggable');
		}
		if (isResizable) {
			classes.push('fc-resizable');
		}
		return classes;
	},
	getEventSkinCss: function(event) {
		var view = this.view;
		var source = event.source || {};
		var eventColor = event.color;
		var sourceColor = source.color;
		var optionColor = view.opt('eventColor');
		var backgroundColor =
			event.backgroundColor ||
			eventColor ||
			source.backgroundColor ||
			sourceColor ||
			view.opt('eventBackgroundColor') ||
			optionColor;
		var borderColor =
			event.borderColor ||
			eventColor ||
			source.borderColor ||
			sourceColor ||
			view.opt('eventBorderColor') ||
			optionColor;
		var textColor =
			event.textColor ||
			source.textColor ||
			view.opt('eventTextColor');
		var statements = [];
		if (backgroundColor) {
			statements.push('background-color:' + backgroundColor);
		}
		if (borderColor) {
			statements.push('border-color:' + borderColor);
		}
		if (textColor) {
			statements.push('color:' + textColor);
		}
		return statements.join(';');
	},
	eventsToSegs: function(events, rangeToSegsFunc) {
		var eventRanges = this.eventsToRanges(events);
		var segs = [];
		var i;
		for (i = 0; i < eventRanges.length; i++) {
			segs.push.apply(
				segs,
				this.eventRangeToSegs(eventRanges[i], rangeToSegsFunc)
			);
		}
		return segs;
	},
	eventsToRanges: function(events) {
		var _this = this;
		var eventsById = groupEventsById(events);
		var ranges = [];
		$.each(eventsById, function(id, eventGroup) {
			if (eventGroup.length) {
				ranges.push.apply(
					ranges,
					isInverseBgEvent(eventGroup[0]) ?
						_this.eventsToInverseRanges(eventGroup) :
						_this.eventsToNormalRanges(eventGroup)
				);
			}
		});
		return ranges;
	},
	eventsToNormalRanges: function(events) {
		var calendar = this.view.calendar;
		var ranges = [];
		var i, event;
		var eventStart, eventEnd;
		for (i = 0; i < events.length; i++) {
			event = events[i];
			eventStart = event.start.clone().stripZone();
			eventEnd = calendar.getEventEnd(event).stripZone();
			ranges.push({
				event: event,
				start: eventStart,
				end: eventEnd,
				eventStartMS: +eventStart,
				eventDurationMS: eventEnd - eventStart
			});
		}
		return ranges;
	},
	eventsToInverseRanges: function(events) {
		var view = this.view;
		var viewStart = view.start.clone().stripZone(); 
		var viewEnd = view.end.clone().stripZone(); 
		var normalRanges = this.eventsToNormalRanges(events); 
		var inverseRanges = [];
		var event0 = events[0]; 
		var start = viewStart; 
		var i, normalRange;
		normalRanges.sort(compareNormalRanges);
		for (i = 0; i < normalRanges.length; i++) {
			normalRange = normalRanges[i];
			if (normalRange.start > start) { 
				inverseRanges.push({
					event: event0,
					start: start,
					end: normalRange.start
				});
			}
			start = normalRange.end;
		}
		if (start < viewEnd) { 
			inverseRanges.push({
				event: event0,
				start: start,
				end: viewEnd
			});
		}
		return inverseRanges;
	},
	eventRangeToSegs: function(eventRange, rangeToSegsFunc) {
		var segs;
		var i, seg;
		if (rangeToSegsFunc) {
			segs = rangeToSegsFunc(eventRange);
		}
		else {
			segs = this.rangeToSegs(eventRange); 
		}
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			seg.event = eventRange.event;
			seg.eventStartMS = eventRange.eventStartMS;
			seg.eventDurationMS = eventRange.eventDurationMS;
		}
		return segs;
	}
});
function isBgEvent(event) { 
	var rendering = getEventRendering(event);
	return rendering === 'background' || rendering === 'inverse-background';
}
function isInverseBgEvent(event) {
	return getEventRendering(event) === 'inverse-background';
}
function getEventRendering(event) {
	return firstDefined((event.source || {}).rendering, event.rendering);
}
function groupEventsById(events) {
	var eventsById = {};
	var i, event;
	for (i = 0; i < events.length; i++) {
		event = events[i];
		(eventsById[event._id] || (eventsById[event._id] = [])).push(event);
	}
	return eventsById;
}
function compareNormalRanges(range1, range2) {
	return range1.eventStartMS - range2.eventStartMS; 
}
function compareSegs(seg1, seg2) {
	return seg1.eventStartMS - seg2.eventStartMS || 
		seg2.eventDurationMS - seg1.eventDurationMS || 
		seg2.event.allDay - seg1.event.allDay || 
		(seg1.event.title || '').localeCompare(seg2.event.title); 
}
fc.compareSegs = compareSegs; 
fc.dataAttrPrefix = '';
function getDraggedElMeta(el) {
	var prefix = fc.dataAttrPrefix;
	var eventProps; 
	var startTime; 
	var duration;
	var stick;
	if (prefix) { prefix += '-'; }
	eventProps = el.data(prefix + 'event') || null;
	if (eventProps) {
		if (typeof eventProps === 'object') {
			eventProps = $.extend({}, eventProps); 
		}
		else { 
			eventProps = {};
		}
		startTime = eventProps.start;
		if (startTime == null) { startTime = eventProps.time; } 
		duration = eventProps.duration;
		stick = eventProps.stick;
		delete eventProps.start;
		delete eventProps.time;
		delete eventProps.duration;
		delete eventProps.stick;
	}
	if (startTime == null) { startTime = el.data(prefix + 'start'); }
	if (startTime == null) { startTime = el.data(prefix + 'time'); } 
	if (duration == null) { duration = el.data(prefix + 'duration'); }
	if (stick == null) { stick = el.data(prefix + 'stick'); }
	startTime = startTime != null ? moment.duration(startTime) : null;
	duration = duration != null ? moment.duration(duration) : null;
	stick = Boolean(stick);
	return { eventProps: eventProps, startTime: startTime, duration: duration, stick: stick };
}
;;
var DayGrid = Grid.extend({
	numbersVisible: false, 
	bottomCoordPadding: 0, 
	breakOnWeeks: null, 
	cellDates: null, 
	dayToCellOffsets: null, 
	rowEls: null, 
	dayEls: null, 
	helperEls: null, 
	render: function(isRigid) {
		var view = this.view;
		var rowCnt = this.rowCnt;
		var colCnt = this.colCnt;
		var cellCnt = rowCnt * colCnt;
		var html = '';
		var row;
		var i, cell;
		for (row = 0; row < rowCnt; row++) {
			html += this.dayRowHtml(row, isRigid);
		}
		this.el.html(html);
		this.rowEls = this.el.find('.fc-row');
		this.dayEls = this.el.find('.fc-day');
		for (i = 0; i < cellCnt; i++) {
			cell = this.getCell(i);
			view.trigger('dayRender', null, cell.start, this.dayEls.eq(i));
		}
		Grid.prototype.render.call(this); 
	},
	destroy: function() {
		this.destroySegPopover();
		Grid.prototype.destroy.call(this); 
	},
	dayRowHtml: function(row, isRigid) {
		var view = this.view;
		var classes = [ 'fc-row', 'fc-week', view.widgetContentClass ];
		if (isRigid) {
			classes.push('fc-rigid');
		}
		return '' +
			'<div class="' + classes.join(' ') + '">' +
				'<div class="fc-bg">' +
					'<table>' +
						this.rowHtml('day', row) + 
					'</table>' +
				'</div>' +
				'<div class="fc-content-skeleton">' +
					'<table>' +
						(this.numbersVisible ?
							'<thead>' +
								this.rowHtml('number', row) + 
							'</thead>' :
							''
							) +
					'</table>' +
				'</div>' +
			'</div>';
	},
	dayCellHtml: function(cell) {
		return this.bgCellHtml(cell);
	},
	computeColHeadFormat: function() {
		if (this.rowCnt > 1) { 
			return 'ddd'; 
		}
		else if (this.colCnt > 1) { 
			return this.view.opt('dayOfMonthFormat'); 
		}
		else { 
			return 'dddd'; 
		}
	},
	computeEventTimeFormat: function() {
		return this.view.opt('extraSmallTimeFormat'); 
	},
	computeDisplayEventEnd: function() {
		return this.colCnt == 1; 
	},
	updateCells: function() {
		var cellDates;
		var firstDay;
		var rowCnt;
		var colCnt;
		this.updateCellDates(); 
		cellDates = this.cellDates;
		if (this.breakOnWeeks) {
			firstDay = cellDates[0].day();
			for (colCnt = 1; colCnt < cellDates.length; colCnt++) {
				if (cellDates[colCnt].day() == firstDay) {
					break;
				}
			}
			rowCnt = Math.ceil(cellDates.length / colCnt);
		}
		else {
			rowCnt = 1;
			colCnt = cellDates.length;
		}
		this.rowCnt = rowCnt;
		this.colCnt = colCnt;
	},
	updateCellDates: function() {
		var view = this.view;
		var date = this.start.clone();
		var dates = [];
		var offset = -1;
		var offsets = [];
		while (date.isBefore(this.end)) { 
			if (view.isHiddenDay(date)) {
				offsets.push(offset + 0.5); 
			}
			else {
				offset++;
				offsets.push(offset);
				dates.push(date.clone());
			}
			date.add(1, 'days');
		}
		this.cellDates = dates;
		this.dayToCellOffsets = offsets;
	},
	computeCellRange: function(cell) {
		var colCnt = this.colCnt;
		var index = cell.row * colCnt + (this.isRTL ? colCnt - cell.col - 1 : cell.col);
		var start = this.cellDates[index].clone();
		var end = start.clone().add(1, 'day');
		return { start: start, end: end };
	},
	getRowEl: function(row) {
		return this.rowEls.eq(row);
	},
	getColEl: function(col) {
		return this.dayEls.eq(col);
	},
	getCellDayEl: function(cell) {
		return this.dayEls.eq(cell.row * this.colCnt + cell.col);
	},
	computeRowCoords: function() {
		var rowCoords = Grid.prototype.computeRowCoords.call(this); 
		rowCoords[rowCoords.length - 1].bottom += this.bottomCoordPadding;
		return rowCoords;
	},
	rangeToSegs: function(range) {
		var isRTL = this.isRTL;
		var rowCnt = this.rowCnt;
		var colCnt = this.colCnt;
		var segs = [];
		var first, last; 
		var row;
		var rowFirst, rowLast; 
		var isStart, isEnd;
		var segFirst, segLast; 
		var seg;
		range = this.view.computeDayRange(range); 
		first = this.dateToCellOffset(range.start);
		last = this.dateToCellOffset(range.end.subtract(1, 'days')); 
		for (row = 0; row < rowCnt; row++) {
			rowFirst = row * colCnt;
			rowLast = rowFirst + colCnt - 1;
			segFirst = Math.max(rowFirst, first);
			segLast = Math.min(rowLast, last);
			segFirst = Math.ceil(segFirst); 
			segLast = Math.floor(segLast); 
			if (segFirst <= segLast) { 
				isStart = segFirst === first;
				isEnd = segLast === last;
				segFirst -= rowFirst;
				segLast -= rowFirst;
				seg = { row: row, isStart: isStart, isEnd: isEnd };
				if (isRTL) {
					seg.leftCol = colCnt - segLast - 1;
					seg.rightCol = colCnt - segFirst - 1;
				}
				else {
					seg.leftCol = segFirst;
					seg.rightCol = segLast;
				}
				segs.push(seg);
			}
		}
		return segs;
	},
	dateToCellOffset: function(date) {
		var offsets = this.dayToCellOffsets;
		var day = date.diff(this.start, 'days');
		if (day < 0) {
			return offsets[0] - 1;
		}
		else if (day >= offsets.length) {
			return offsets[offsets.length - 1] + 1;
		}
		else {
			return offsets[day];
		}
	},
	renderDrag: function(dropLocation, seg) {
		var opacity;
		this.renderHighlight(
			this.view.calendar.ensureVisibleEventRange(dropLocation) 
		);
		if (seg && !seg.el.closest(this.el).length) {
			this.renderRangeHelper(dropLocation, seg);
			opacity = this.view.opt('dragOpacity');
			if (opacity !== undefined) {
				this.helperEls.css('opacity', opacity);
			}
			return true; 
		}
	},
	destroyDrag: function() {
		this.destroyHighlight();
		this.destroyHelper();
	},
	renderEventResize: function(range, seg) {
		this.renderHighlight(range);
		this.renderRangeHelper(range, seg);
	},
	destroyEventResize: function() {
		this.destroyHighlight();
		this.destroyHelper();
	},
	renderHelper: function(event, sourceSeg) {
		var helperNodes = [];
		var segs = this.eventsToSegs([ event ]);
		var rowStructs;
		segs = this.renderFgSegEls(segs); 
		rowStructs = this.renderSegRows(segs);
		this.rowEls.each(function(row, rowNode) {
			var rowEl = $(rowNode); 
			var skeletonEl = $('<div class="fc-helper-skeleton"><table/></div>'); 
			var skeletonTop;
			if (sourceSeg && sourceSeg.row === row) {
				skeletonTop = sourceSeg.el.position().top;
			}
			else {
				skeletonTop = rowEl.find('.fc-content-skeleton tbody').position().top;
			}
			skeletonEl.css('top', skeletonTop)
				.find('table')
					.append(rowStructs[row].tbodyEl);
			rowEl.append(skeletonEl);
			helperNodes.push(skeletonEl[0]);
		});
		this.helperEls = $(helperNodes); 
	},
	destroyHelper: function() {
		if (this.helperEls) {
			this.helperEls.remove();
			this.helperEls = null;
		}
	},
	fillSegTag: 'td', 
	renderFill: function(type, segs) {
		var nodes = [];
		var i, seg;
		var skeletonEl;
		segs = this.renderFillSegEls(type, segs); 
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			skeletonEl = this.renderFillRow(type, seg);
			this.rowEls.eq(seg.row).append(skeletonEl);
			nodes.push(skeletonEl[0]);
		}
		this.elsByFill[type] = $(nodes);
		return segs;
	},
	renderFillRow: function(type, seg) {
		var colCnt = this.colCnt;
		var startCol = seg.leftCol;
		var endCol = seg.rightCol + 1;
		var skeletonEl;
		var trEl;
		skeletonEl = $(
			'<div class="fc-' + type.toLowerCase() + '-skeleton">' +
				'<table><tr/></table>' +
			'</div>'
		);
		trEl = skeletonEl.find('tr');
		if (startCol > 0) {
			trEl.append('<td colspan="' + startCol + '"/>');
		}
		trEl.append(
			seg.el.attr('colspan', endCol - startCol)
		);
		if (endCol < colCnt) {
			trEl.append('<td colspan="' + (colCnt - endCol) + '"/>');
		}
		this.bookendCells(trEl, type);
		return skeletonEl;
	}
});
;;
DayGrid.mixin({
	rowStructs: null, 
	destroyEvents: function() {
		this.destroySegPopover(); 
		Grid.prototype.destroyEvents.apply(this, arguments); 
	},
	getEventSegs: function() {
		return Grid.prototype.getEventSegs.call(this) 
			.concat(this.popoverSegs || []); 
	},
	renderBgSegs: function(segs) {
		var allDaySegs = $.grep(segs, function(seg) {
			return seg.event.allDay;
		});
		return Grid.prototype.renderBgSegs.call(this, allDaySegs); 
	},
	renderFgSegs: function(segs) {
		var rowStructs;
		segs = this.renderFgSegEls(segs);
		rowStructs = this.rowStructs = this.renderSegRows(segs);
		this.rowEls.each(function(i, rowNode) {
			$(rowNode).find('.fc-content-skeleton > table').append(
				rowStructs[i].tbodyEl
			);
		});
		return segs; 
	},
	destroyFgSegs: function() {
		var rowStructs = this.rowStructs || [];
		var rowStruct;
		while ((rowStruct = rowStructs.pop())) {
			rowStruct.tbodyEl.remove();
		}
		this.rowStructs = null;
	},
	renderSegRows: function(segs) {
		var rowStructs = [];
		var segRows;
		var row;
		segRows = this.groupSegRows(segs); 
		for (row = 0; row < segRows.length; row++) {
			rowStructs.push(
				this.renderSegRow(row, segRows[row])
			);
		}
		return rowStructs;
	},
	fgSegHtml: function(seg, disableResizing) {
		var view = this.view;
		var event = seg.event;
		var isDraggable = view.isEventDraggable(event);
		var isResizable = !disableResizing && event.allDay && seg.isEnd && view.isEventResizable(event);
		var classes = this.getSegClasses(seg, isDraggable, isResizable);
		var skinCss = this.getEventSkinCss(event);
		var timeHtml = '';
		var titleHtml;
		classes.unshift('fc-day-grid-event');
		if (!event.allDay && seg.isStart) {
			timeHtml = '<span class="fc-time">' + htmlEscape(this.getEventTimeText(event)) + '</span>';
		}
		titleHtml =
			'<span class="fc-title">' +
				(htmlEscape(event.title || '') || '&nbsp;') + 
			'</span>';
		return '<a class="' + classes.join(' ') + '"' +
				(event.url ?
					' href="' + htmlEscape(event.url) + '"' :
					''
					) +
				(skinCss ?
					' style="' + skinCss + '"' :
					''
					) +
			'>' +
				'<div class="fc-content">' +
					(this.isRTL ?
						titleHtml + ' ' + timeHtml : 
						timeHtml + ' ' + titleHtml   
						) +
				'</div>' +
				(isResizable ?
					'<div class="fc-resizer"/>' :
					''
					) +
			'</a>';
	},
	renderSegRow: function(row, rowSegs) {
		var colCnt = this.colCnt;
		var segLevels = this.buildSegLevels(rowSegs); 
		var levelCnt = Math.max(1, segLevels.length); 
		var tbody = $('<tbody/>');
		var segMatrix = []; 
		var cellMatrix = []; 
		var loneCellMatrix = []; 
		var i, levelSegs;
		var col;
		var tr;
		var j, seg;
		var td;
		function emptyCellsUntil(endCol) {
			while (col < endCol) {
				td = (loneCellMatrix[i - 1] || [])[col];
				if (td) {
					td.attr(
						'rowspan',
						parseInt(td.attr('rowspan') || 1, 10) + 1
					);
				}
				else {
					td = $('<td/>');
					tr.append(td);
				}
				cellMatrix[i][col] = td;
				loneCellMatrix[i][col] = td;
				col++;
			}
		}
		for (i = 0; i < levelCnt; i++) { 
			levelSegs = segLevels[i];
			col = 0;
			tr = $('<tr/>');
			segMatrix.push([]);
			cellMatrix.push([]);
			loneCellMatrix.push([]);
			if (levelSegs) {
				for (j = 0; j < levelSegs.length; j++) { 
					seg = levelSegs[j];
					emptyCellsUntil(seg.leftCol);
					td = $('<td class="fc-event-container"/>').append(seg.el);
					if (seg.leftCol != seg.rightCol) {
						td.attr('colspan', seg.rightCol - seg.leftCol + 1);
					}
					else { 
						loneCellMatrix[i][col] = td;
					}
					while (col <= seg.rightCol) {
						cellMatrix[i][col] = td;
						segMatrix[i][col] = seg;
						col++;
					}
					tr.append(td);
				}
			}
			emptyCellsUntil(colCnt); 
			this.bookendCells(tr, 'eventSkeleton');
			tbody.append(tr);
		}
		return { 
			row: row, 
			tbodyEl: tbody,
			cellMatrix: cellMatrix,
			segMatrix: segMatrix,
			segLevels: segLevels,
			segs: rowSegs
		};
	},
	buildSegLevels: function(segs) {
		var levels = [];
		var i, seg;
		var j;
		segs.sort(compareSegs);
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			for (j = 0; j < levels.length; j++) {
				if (!isDaySegCollision(seg, levels[j])) {
					break;
				}
			}
			seg.level = j;
			(levels[j] || (levels[j] = [])).push(seg);
		}
		for (j = 0; j < levels.length; j++) {
			levels[j].sort(compareDaySegCols);
		}
		return levels;
	},
	groupSegRows: function(segs) {
		var segRows = [];
		var i;
		for (i = 0; i < this.rowCnt; i++) {
			segRows.push([]);
		}
		for (i = 0; i < segs.length; i++) {
			segRows[segs[i].row].push(segs[i]);
		}
		return segRows;
	}
});
function isDaySegCollision(seg, otherSegs) {
	var i, otherSeg;
	for (i = 0; i < otherSegs.length; i++) {
		otherSeg = otherSegs[i];
		if (
			otherSeg.leftCol <= seg.rightCol &&
			otherSeg.rightCol >= seg.leftCol
		) {
			return true;
		}
	}
	return false;
}
function compareDaySegCols(a, b) {
	return a.leftCol - b.leftCol;
}
;;
DayGrid.mixin({
	segPopover: null, 
	popoverSegs: null, 
	destroySegPopover: function() {
		if (this.segPopover) {
			this.segPopover.hide(); 
		}
	},
	limitRows: function(levelLimit) {
		var rowStructs = this.rowStructs || [];
		var row; 
		var rowLevelLimit;
		for (row = 0; row < rowStructs.length; row++) {
			this.unlimitRow(row);
			if (!levelLimit) {
				rowLevelLimit = false;
			}
			else if (typeof levelLimit === 'number') {
				rowLevelLimit = levelLimit;
			}
			else {
				rowLevelLimit = this.computeRowLevelLimit(row);
			}
			if (rowLevelLimit !== false) {
				this.limitRow(row, rowLevelLimit);
			}
		}
	},
	computeRowLevelLimit: function(row) {
		var rowEl = this.rowEls.eq(row); 
		var rowHeight = rowEl.height(); 
		var trEls = this.rowStructs[row].tbodyEl.children();
		var i, trEl;
		for (i = 0; i < trEls.length; i++) {
			trEl = trEls.eq(i).removeClass('fc-limited'); 
			if (trEl.position().top + trEl.outerHeight() > rowHeight) {
				return i;
			}
		}
		return false; 
	},
	limitRow: function(row, levelLimit) {
		var _this = this;
		var rowStruct = this.rowStructs[row];
		var moreNodes = []; 
		var col = 0; 
		var cell;
		var levelSegs; 
		var cellMatrix; 
		var limitedNodes; 
		var i, seg;
		var segsBelow; 
		var totalSegsBelow; 
		var colSegsBelow; 
		var td, rowspan;
		var segMoreNodes; 
		var j;
		var moreTd, moreWrap, moreLink;
		function emptyCellsUntil(endCol) { 
			while (col < endCol) {
				cell = _this.getCell(row, col);
				segsBelow = _this.getCellSegs(cell, levelLimit);
				if (segsBelow.length) {
					td = cellMatrix[levelLimit - 1][col];
					moreLink = _this.renderMoreLink(cell, segsBelow);
					moreWrap = $('<div/>').append(moreLink);
					td.append(moreWrap);
					moreNodes.push(moreWrap[0]);
				}
				col++;
			}
		}
		if (levelLimit && levelLimit < rowStruct.segLevels.length) { 
			levelSegs = rowStruct.segLevels[levelLimit - 1];
			cellMatrix = rowStruct.cellMatrix;
			limitedNodes = rowStruct.tbodyEl.children().slice(levelLimit) 
				.addClass('fc-limited').get(); 
			for (i = 0; i < levelSegs.length; i++) {
				seg = levelSegs[i];
				emptyCellsUntil(seg.leftCol); 
				colSegsBelow = [];
				totalSegsBelow = 0;
				while (col <= seg.rightCol) {
					cell = this.getCell(row, col);
					segsBelow = this.getCellSegs(cell, levelLimit);
					colSegsBelow.push(segsBelow);
					totalSegsBelow += segsBelow.length;
					col++;
				}
				if (totalSegsBelow) { 
					td = cellMatrix[levelLimit - 1][seg.leftCol]; 
					rowspan = td.attr('rowspan') || 1;
					segMoreNodes = [];
					for (j = 0; j < colSegsBelow.length; j++) {
						moreTd = $('<td class="fc-more-cell"/>').attr('rowspan', rowspan);
						segsBelow = colSegsBelow[j];
						cell = this.getCell(row, seg.leftCol + j);
						moreLink = this.renderMoreLink(cell, [ seg ].concat(segsBelow)); 
						moreWrap = $('<div/>').append(moreLink);
						moreTd.append(moreWrap);
						segMoreNodes.push(moreTd[0]);
						moreNodes.push(moreTd[0]);
					}
					td.addClass('fc-limited').after($(segMoreNodes)); 
					limitedNodes.push(td[0]);
				}
			}
			emptyCellsUntil(this.colCnt); 
			rowStruct.moreEls = $(moreNodes); 
			rowStruct.limitedEls = $(limitedNodes); 
		}
	},
	unlimitRow: function(row) {
		var rowStruct = this.rowStructs[row];
		if (rowStruct.moreEls) {
			rowStruct.moreEls.remove();
			rowStruct.moreEls = null;
		}
		if (rowStruct.limitedEls) {
			rowStruct.limitedEls.removeClass('fc-limited');
			rowStruct.limitedEls = null;
		}
	},
	renderMoreLink: function(cell, hiddenSegs) {
		var _this = this;
		var view = this.view;
		return $('<a class="fc-more"/>')
			.text(
				this.getMoreLinkText(hiddenSegs.length)
			)
			.on('click', function(ev) {
				var clickOption = view.opt('eventLimitClick');
				var date = cell.start;
				var moreEl = $(this);
				var dayEl = _this.getCellDayEl(cell);
				var allSegs = _this.getCellSegs(cell);
				var reslicedAllSegs = _this.resliceDaySegs(allSegs, date);
				var reslicedHiddenSegs = _this.resliceDaySegs(hiddenSegs, date);
				if (typeof clickOption === 'function') {
					clickOption = view.trigger('eventLimitClick', null, {
						date: date,
						dayEl: dayEl,
						moreEl: moreEl,
						segs: reslicedAllSegs,
						hiddenSegs: reslicedHiddenSegs
					}, ev);
				}
				if (clickOption === 'popover') {
					_this.showSegPopover(cell, moreEl, reslicedAllSegs);
				}
				else if (typeof clickOption === 'string') { 
					view.calendar.zoomTo(date, clickOption);
				}
			});
	},
	showSegPopover: function(cell, moreLink, segs) {
		var _this = this;
		var view = this.view;
		var moreWrap = moreLink.parent(); 
		var topEl; 
		var options;
		if (this.rowCnt == 1) {
			topEl = view.el; 
		}
		else {
			topEl = this.rowEls.eq(cell.row); 
		}
		options = {
			className: 'fc-more-popover',
			content: this.renderSegPopoverContent(cell, segs),
			parentEl: this.el,
			top: topEl.offset().top,
			autoHide: true, 
			viewportConstrain: view.opt('popoverViewportConstrain'),
			hide: function() {
				_this.segPopover.destroy();
				_this.segPopover = null;
				_this.popoverSegs = null;
			}
		};
		if (this.isRTL) {
			options.right = moreWrap.offset().left + moreWrap.outerWidth() + 1; 
		}
		else {
			options.left = moreWrap.offset().left - 1; 
		}
		this.segPopover = new Popover(options);
		this.segPopover.show();
	},
	renderSegPopoverContent: function(cell, segs) {
		var view = this.view;
		var isTheme = view.opt('theme');
		var title = cell.start.format(view.opt('dayPopoverFormat'));
		var content = $(
			'<div class="fc-header ' + view.widgetHeaderClass + '">' +
				'<span class="fc-close ' +
					(isTheme ? 'ui-icon ui-icon-closethick' : 'fc-icon fc-icon-x') +
				'"></span>' +
				'<span class="fc-title">' +
					htmlEscape(title) +
				'</span>' +
				'<div class="fc-clear"/>' +
			'</div>' +
			'<div class="fc-body ' + view.widgetContentClass + '">' +
				'<div class="fc-event-container"></div>' +
			'</div>'
		);
		var segContainer = content.find('.fc-event-container');
		var i;
		segs = this.renderFgSegEls(segs, true); 
		this.popoverSegs = segs;
		for (i = 0; i < segs.length; i++) {
			segs[i].cell = cell;
			segContainer.append(segs[i].el);
		}
		return content;
	},
	resliceDaySegs: function(segs, dayDate) {
		var events = $.map(segs, function(seg) {
			return seg.event;
		});
		var dayStart = dayDate.clone().stripTime();
		var dayEnd = dayStart.clone().add(1, 'days');
		var dayRange = { start: dayStart, end: dayEnd };
		return this.eventsToSegs(
			events,
			function(range) {
				var seg = intersectionToSeg(range, dayRange); 
				return seg ? [ seg ] : []; 
			}
		);
	},
	getMoreLinkText: function(num) {
		var opt = this.view.opt('eventLimitText');
		if (typeof opt === 'function') {
			return opt(num);
		}
		else {
			return '+' + num + ' ' + opt;
		}
	},
	getCellSegs: function(cell, startLevel) {
		var segMatrix = this.rowStructs[cell.row].segMatrix;
		var level = startLevel || 0;
		var segs = [];
		var seg;
		while (level < segMatrix.length) {
			seg = segMatrix[level][cell.col];
			if (seg) {
				segs.push(seg);
			}
			level++;
		}
		return segs;
	}
});
;;
var TimeGrid = Grid.extend({
	slotDuration: null, 
	snapDuration: null, 
	minTime: null, 
	maxTime: null, 
	axisFormat: null, 
	dayEls: null, 
	slatEls: null, 
	slatTops: null, 
	helperEl: null, 
	businessHourSegs: null,
	constructor: function() {
		Grid.apply(this, arguments); 
		this.processOptions();
	},
	render: function() {
		this.el.html(this.renderHtml());
		this.dayEls = this.el.find('.fc-day');
		this.slatEls = this.el.find('.fc-slats tr');
		this.computeSlatTops();
		this.renderBusinessHours();
		Grid.prototype.render.call(this); 
	},
	renderBusinessHours: function() {
		var events = this.view.calendar.getBusinessHoursEvents();
		this.businessHourSegs = this.renderFill('businessHours', this.eventsToSegs(events), 'bgevent');
	},
	renderHtml: function() {
		return '' +
			'<div class="fc-bg">' +
				'<table>' +
					this.rowHtml('slotBg') + 
				'</table>' +
			'</div>' +
			'<div class="fc-slats">' +
				'<table>' +
					this.slatRowHtml() +
				'</table>' +
			'</div>';
	},
	slotBgCellHtml: function(cell) {
		return this.bgCellHtml(cell);
	},
	slatRowHtml: function() {
		var view = this.view;
		var isRTL = this.isRTL;
		var html = '';
		var slotNormal = this.slotDuration.asMinutes() % 15 === 0;
		var slotTime = moment.duration(+this.minTime); 
		var slotDate; 
		var minutes;
		var axisHtml;
		while (slotTime < this.maxTime) {
			slotDate = this.start.clone().time(slotTime); 
			minutes = slotDate.minutes();
			axisHtml =
				'<td class="fc-axis fc-time ' + view.widgetContentClass + '" ' + view.axisStyleAttr() + '>' +
					((!slotNormal || !minutes) ? 
						'<span>' + 
							htmlEscape(slotDate.format(this.axisFormat)) +
						'</span>' :
						''
						) +
				'</td>';
			html +=
				'<tr ' + (!minutes ? '' : 'class="fc-minor"') + '>' +
					(!isRTL ? axisHtml : '') +
					'<td class="' + view.widgetContentClass + '"/>' +
					(isRTL ? axisHtml : '') +
				"</tr>";
			slotTime.add(this.slotDuration);
		}
		return html;
	},
	processOptions: function() {
		var view = this.view;
		var slotDuration = view.opt('slotDuration');
		var snapDuration = view.opt('snapDuration');
		slotDuration = moment.duration(slotDuration);
		snapDuration = snapDuration ? moment.duration(snapDuration) : slotDuration;
		this.slotDuration = slotDuration;
		this.snapDuration = snapDuration;
		this.minTime = moment.duration(view.opt('minTime'));
		this.maxTime = moment.duration(view.opt('maxTime'));
		this.axisFormat = view.opt('axisFormat') || view.opt('smallTimeFormat');
	},
	computeColHeadFormat: function() {
		if (this.colCnt > 1) { 
			return this.view.opt('dayOfMonthFormat'); 
		}
		else { 
			return 'dddd'; 
		}
	},
	computeEventTimeFormat: function() {
		return this.view.opt('noMeridiemTimeFormat'); 
	},
	computeDisplayEventEnd: function() {
		return true;
	},
	updateCells: function() {
		var view = this.view;
		var colData = [];
		var date;
		date = this.start.clone();
		while (date.isBefore(this.end)) {
			colData.push({
				day: date.clone()
			});
			date.add(1, 'day');
			date = view.skipHiddenDays(date);
		}
		if (this.isRTL) {
			colData.reverse();
		}
		this.colData = colData;
		this.colCnt = colData.length;
		this.rowCnt = Math.ceil((this.maxTime - this.minTime) / this.snapDuration); 
	},
	computeCellRange: function(cell) {
		var time = this.computeSnapTime(cell.row);
		var start = this.view.calendar.rezoneDate(cell.day).time(time);
		var end = start.clone().add(this.snapDuration);
		return { start: start, end: end };
	},
	getColEl: function(col) {
		return this.dayEls.eq(col);
	},
	computeSnapTime: function(row) {
		return moment.duration(this.minTime + this.snapDuration * row);
	},
	rangeToSegs: function(range) {
		var colCnt = this.colCnt;
		var segs = [];
		var seg;
		var col;
		var colDate;
		var colRange;
		range = {
			start: range.start.clone().stripZone(),
			end: range.end.clone().stripZone()
		};
		for (col = 0; col < colCnt; col++) {
			colDate = this.colData[col].day; 
			colRange = {
				start: colDate.clone().time(this.minTime),
				end: colDate.clone().time(this.maxTime)
			};
			seg = intersectionToSeg(range, colRange); 
			if (seg) {
				seg.col = col;
				segs.push(seg);
			}
		}
		return segs;
	},
	resize: function() {
		this.computeSlatTops();
		this.updateSegVerticals();
	},
	computeRowCoords: function() {
		var originTop = this.el.offset().top;
		var items = [];
		var i;
		var item;
		for (i = 0; i < this.rowCnt; i++) {
			item = {
				top: originTop + this.computeTimeTop(this.computeSnapTime(i))
			};
			if (i > 0) {
				items[i - 1].bottom = item.top;
			}
			items.push(item);
		}
		item.bottom = item.top + this.computeTimeTop(this.computeSnapTime(i));
		return items;
	},
	computeDateTop: function(date, startOfDayDate) {
		return this.computeTimeTop(
			moment.duration(
				date.clone().stripZone() - startOfDayDate.clone().stripTime()
			)
		);
	},
	computeTimeTop: function(time) {
		var slatCoverage = (time - this.minTime) / this.slotDuration; 
		var slatIndex;
		var slatRemainder;
		var slatTop;
		var slatBottom;
		slatCoverage = Math.max(0, slatCoverage);
		slatCoverage = Math.min(this.slatEls.length, slatCoverage);
		slatIndex = Math.floor(slatCoverage); 
		slatRemainder = slatCoverage - slatIndex;
		slatTop = this.slatTops[slatIndex]; 
		if (slatRemainder) { 
			slatBottom = this.slatTops[slatIndex + 1];
			return slatTop + (slatBottom - slatTop) * slatRemainder; 
		}
		else {
			return slatTop;
		}
	},
	computeSlatTops: function() {
		var tops = [];
		var top;
		this.slatEls.each(function(i, node) {
			top = $(node).position().top;
			tops.push(top);
		});
		tops.push(top + this.slatEls.last().outerHeight()); 
		this.slatTops = tops;
	},
	renderDrag: function(dropLocation, seg) {
		var opacity;
		if (seg) { 
			this.renderRangeHelper(dropLocation, seg);
			opacity = this.view.opt('dragOpacity');
			if (opacity !== undefined) {
				this.helperEl.css('opacity', opacity);
			}
			return true; 
		}
		else {
			this.renderHighlight(
				this.view.calendar.ensureVisibleEventRange(dropLocation) 
			);
		}
	},
	destroyDrag: function() {
		this.destroyHelper();
		this.destroyHighlight();
	},
	renderEventResize: function(range, seg) {
		this.renderRangeHelper(range, seg);
	},
	destroyEventResize: function() {
		this.destroyHelper();
	},
	renderHelper: function(event, sourceSeg) {
		var segs = this.eventsToSegs([ event ]);
		var tableEl;
		var i, seg;
		var sourceEl;
		segs = this.renderFgSegEls(segs); 
		tableEl = this.renderSegTable(segs);
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			if (sourceSeg && sourceSeg.col === seg.col) {
				sourceEl = sourceSeg.el;
				seg.el.css({
					left: sourceEl.css('left'),
					right: sourceEl.css('right'),
					'margin-left': sourceEl.css('margin-left'),
					'margin-right': sourceEl.css('margin-right')
				});
			}
		}
		this.helperEl = $('<div class="fc-helper-skeleton"/>')
			.append(tableEl)
				.appendTo(this.el);
	},
	destroyHelper: function() {
		if (this.helperEl) {
			this.helperEl.remove();
			this.helperEl = null;
		}
	},
	renderSelection: function(range) {
		if (this.view.opt('selectHelper')) { 
			this.renderRangeHelper(range);
		}
		else {
			this.renderHighlight(range);
		}
	},
	destroySelection: function() {
		this.destroyHelper();
		this.destroyHighlight();
	},
	renderFill: function(type, segs, className) {
		var segCols;
		var skeletonEl;
		var trEl;
		var col, colSegs;
		var tdEl;
		var containerEl;
		var dayDate;
		var i, seg;
		if (segs.length) {
			segs = this.renderFillSegEls(type, segs); 
			segCols = this.groupSegCols(segs); 
			className = className || type.toLowerCase();
			skeletonEl = $(
				'<div class="fc-' + className + '-skeleton">' +
					'<table><tr/></table>' +
				'</div>'
			);
			trEl = skeletonEl.find('tr');
			for (col = 0; col < segCols.length; col++) {
				colSegs = segCols[col];
				tdEl = $('<td/>').appendTo(trEl);
				if (colSegs.length) {
					containerEl = $('<div class="fc-' + className + '-container"/>').appendTo(tdEl);
					dayDate = this.colData[col].day;
					for (i = 0; i < colSegs.length; i++) {
						seg = colSegs[i];
						containerEl.append(
							seg.el.css({
								top: this.computeDateTop(seg.start, dayDate),
								bottom: -this.computeDateTop(seg.end, dayDate) 
							})
						);
					}
				}
			}
			this.bookendCells(trEl, type);
			this.el.append(skeletonEl);
			this.elsByFill[type] = skeletonEl;
		}
		return segs;
	}
});
;;
TimeGrid.mixin({
	eventSkeletonEl: null, 
	renderFgSegs: function(segs) {
		segs = this.renderFgSegEls(segs); 
		this.el.append(
			this.eventSkeletonEl = $('<div class="fc-content-skeleton"/>')
				.append(this.renderSegTable(segs))
		);
		return segs; 
	},
	destroyFgSegs: function(segs) {
		if (this.eventSkeletonEl) {
			this.eventSkeletonEl.remove();
			this.eventSkeletonEl = null;
		}
	},
	renderSegTable: function(segs) {
		var tableEl = $('<table><tr/></table>');
		var trEl = tableEl.find('tr');
		var segCols;
		var i, seg;
		var col, colSegs;
		var containerEl;
		segCols = this.groupSegCols(segs); 
		this.computeSegVerticals(segs); 
		for (col = 0; col < segCols.length; col++) { 
			colSegs = segCols[col];
			placeSlotSegs(colSegs); 
			containerEl = $('<div class="fc-event-container"/>');
			for (i = 0; i < colSegs.length; i++) {
				seg = colSegs[i];
				seg.el.css(this.generateSegPositionCss(seg));
				if (seg.bottom - seg.top < 30) {
					seg.el.addClass('fc-short');
				}
				containerEl.append(seg.el);
			}
			trEl.append($('<td/>').append(containerEl));
		}
		this.bookendCells(trEl, 'eventSkeleton');
		return tableEl;
	},
	updateSegVerticals: function() {
		var allSegs = (this.segs || []).concat(this.businessHourSegs || []);
		var i;
		this.computeSegVerticals(allSegs);
		for (i = 0; i < allSegs.length; i++) {
			allSegs[i].el.css(
				this.generateSegVerticalCss(allSegs[i])
			);
		}
	},
	computeSegVerticals: function(segs) {
		var i, seg;
		for (i = 0; i < segs.length; i++) {
			seg = segs[i];
			seg.top = this.computeDateTop(seg.start, seg.start);
			seg.bottom = this.computeDateTop(seg.end, seg.start);
		}
	},
	fgSegHtml: function(seg, disableResizing) {
		var view = this.view;
		var event = seg.event;
		var isDraggable = view.isEventDraggable(event);
		var isResizable = !disableResizing && seg.isEnd && view.isEventResizable(event);
		var classes = this.getSegClasses(seg, isDraggable, isResizable);
		var skinCss = this.getEventSkinCss(event);
		var timeText;
		var fullTimeText; 
		var startTimeText; 
		classes.unshift('fc-time-grid-event');
		if (view.isMultiDayEvent(event)) { 
			if (seg.isStart || seg.isEnd) {
				timeText = this.getEventTimeText(seg);
				fullTimeText = this.getEventTimeText(seg, 'LT');
				startTimeText = this.getEventTimeText({ start: seg.start });
			}
		} else {
			timeText = this.getEventTimeText(event);
			fullTimeText = this.getEventTimeText(event, 'LT');
			startTimeText = this.getEventTimeText({ start: event.start });
		}
		return '<a class="' + classes.join(' ') + '"' +
			(event.url ?
				' href="' + htmlEscape(event.url) + '"' :
				''
				) +
			(skinCss ?
				' style="' + skinCss + '"' :
				''
				) +
			'>' +
				'<div class="fc-content">' +
					(timeText ?
						'<div class="fc-time"' +
						' data-start="' + htmlEscape(startTimeText) + '"' +
						' data-full="' + htmlEscape(fullTimeText) + '"' +
						'>' +
							'<span>' + htmlEscape(timeText) + '</span>' +
						'</div>' :
						''
						) +
					(event.title ?
						'<div class="fc-title">' +
							htmlEscape(event.title) +
						'</div>' :
						''
						) +
				'</div>' +
				'<div class="fc-bg"/>' +
				(isResizable ?
					'<div class="fc-resizer"/>' :
					''
					) +
			'</a>';
	},
	generateSegPositionCss: function(seg) {
		var shouldOverlap = this.view.opt('slotEventOverlap');
		var backwardCoord = seg.backwardCoord; 
		var forwardCoord = seg.forwardCoord; 
		var props = this.generateSegVerticalCss(seg); 
		var left; 
		var right; 
		if (shouldOverlap) {
			forwardCoord = Math.min(1, backwardCoord + (forwardCoord - backwardCoord) * 2);
		}
		if (this.isRTL) {
			left = 1 - forwardCoord;
			right = backwardCoord;
		}
		else {
			left = backwardCoord;
			right = 1 - forwardCoord;
		}
		props.zIndex = seg.level + 1; 
		props.left = left * 100 + '%';
		props.right = right * 100 + '%';
		if (shouldOverlap && seg.forwardPressure) {
			props[this.isRTL ? 'marginLeft' : 'marginRight'] = 10 * 2; 
		}
		return props;
	},
	generateSegVerticalCss: function(seg) {
		return {
			top: seg.top,
			bottom: -seg.bottom 
		};
	},
	groupSegCols: function(segs) {
		var segCols = [];
		var i;
		for (i = 0; i < this.colCnt; i++) {
			segCols.push([]);
		}
		for (i = 0; i < segs.length; i++) {
			segCols[segs[i].col].push(segs[i]);
		}
		return segCols;
	}
});
function placeSlotSegs(segs) {
	var levels;
	var level0;
	var i;
	segs.sort(compareSegs); 
	levels = buildSlotSegLevels(segs);
	computeForwardSlotSegs(levels);
	if ((level0 = levels[0])) {
		for (i = 0; i < level0.length; i++) {
			computeSlotSegPressures(level0[i]);
		}
		for (i = 0; i < level0.length; i++) {
			computeSlotSegCoords(level0[i], 0, 0);
		}
	}
}
function buildSlotSegLevels(segs) {
	var levels = [];
	var i, seg;
	var j;
	for (i=0; i<segs.length; i++) {
		seg = segs[i];
		for (j=0; j<levels.length; j++) {
			if (!computeSlotSegCollisions(seg, levels[j]).length) {
				break;
			}
		}
		seg.level = j;
		(levels[j] || (levels[j] = [])).push(seg);
	}
	return levels;
}
function computeForwardSlotSegs(levels) {
	var i, level;
	var j, seg;
	var k;
	for (i=0; i<levels.length; i++) {
		level = levels[i];
		for (j=0; j<level.length; j++) {
			seg = level[j];
			seg.forwardSegs = [];
			for (k=i+1; k<levels.length; k++) {
				computeSlotSegCollisions(seg, levels[k], seg.forwardSegs);
			}
		}
	}
}
function computeSlotSegPressures(seg) {
	var forwardSegs = seg.forwardSegs;
	var forwardPressure = 0;
	var i, forwardSeg;
	if (seg.forwardPressure === undefined) { 
		for (i=0; i<forwardSegs.length; i++) {
			forwardSeg = forwardSegs[i];
			computeSlotSegPressures(forwardSeg);
			forwardPressure = Math.max(
				forwardPressure,
				1 + forwardSeg.forwardPressure
			);
		}
		seg.forwardPressure = forwardPressure;
	}
}
function computeSlotSegCoords(seg, seriesBackwardPressure, seriesBackwardCoord) {
	var forwardSegs = seg.forwardSegs;
	var i;
	if (seg.forwardCoord === undefined) { 
		if (!forwardSegs.length) {
			seg.forwardCoord = 1;
		}
		else {
			forwardSegs.sort(compareForwardSlotSegs);
			computeSlotSegCoords(forwardSegs[0], seriesBackwardPressure + 1, seriesBackwardCoord);
			seg.forwardCoord = forwardSegs[0].backwardCoord;
		}
		seg.backwardCoord = seg.forwardCoord -
			(seg.forwardCoord - seriesBackwardCoord) / 
			(seriesBackwardPressure + 1); 
		for (i=0; i<forwardSegs.length; i++) {
			computeSlotSegCoords(forwardSegs[i], 0, seg.forwardCoord);
		}
	}
}
function computeSlotSegCollisions(seg, otherSegs, results) {
	results = results || [];
	for (var i=0; i<otherSegs.length; i++) {
		if (isSlotSegCollision(seg, otherSegs[i])) {
			results.push(otherSegs[i]);
		}
	}
	return results;
}
function isSlotSegCollision(seg1, seg2) {
	return seg1.bottom > seg2.top && seg1.top < seg2.bottom;
}
function compareForwardSlotSegs(seg1, seg2) {
	return seg2.forwardPressure - seg1.forwardPressure ||
		(seg1.backwardCoord || 0) - (seg2.backwardCoord || 0) ||
		compareSegs(seg1, seg2);
}
;;
var View = fc.View = Class.extend({
	type: null, 
	name: null, 
	calendar: null, 
	options: null, 
	coordMap: null, 
	el: null, 
	start: null,
	end: null, 
	intervalStart: null,
	intervalEnd: null, 
	intervalDuration: null, 
	intervalUnit: null, 
	isSelected: false, 
	scrollerEl: null, 
	scrollTop: null, 
	widgetHeaderClass: null,
	widgetContentClass: null,
	highlightStateClass: null,
	nextDayThreshold: null,
	isHiddenDayHash: null,
	documentMousedownProxy: null, 
	constructor: function(calendar, viewOptions, viewType) {
		this.calendar = calendar;
		this.options = viewOptions;
		this.type = this.name = viewType; 
		this.nextDayThreshold = moment.duration(this.opt('nextDayThreshold'));
		this.initTheming();
		this.initHiddenDays();
		this.documentMousedownProxy = $.proxy(this, 'documentMousedown');
		this.initialize();
	},
	initialize: function() {
	},
	opt: function(name) {
		var val;
		val = this.options[name]; 
		if (val !== undefined) {
			return val;
		}
		val = this.calendar.options[name];
		if ($.isPlainObject(val) && !isForcedAtomicOption(name)) { 
			return smartProperty(val, this.type);
		}
		return val;
	},
	trigger: function(name, thisObj) { 
		var calendar = this.calendar;
		return calendar.trigger.apply(
			calendar,
			[name, thisObj || this].concat(
				Array.prototype.slice.call(arguments, 2), 
				[ this ] 
			)
		);
	},
	setDate: function(date) {
		this.setRange(this.computeRange(date));
	},
	setRange: function(range) {
		$.extend(this, range);
	},
	computeRange: function(date) {
		var intervalDuration = moment.duration(this.opt('duration') || this.constructor.duration || { days: 1 });
		var intervalUnit = computeIntervalUnit(intervalDuration);
		var intervalStart = date.clone().startOf(intervalUnit);
		var intervalEnd = intervalStart.clone().add(intervalDuration);
		var start, end;
		if (computeIntervalAs('days', intervalDuration)) { 
			intervalStart.stripTime();
			intervalEnd.stripTime();
		}
		else { 
			if (!intervalStart.hasTime()) {
				intervalStart = this.calendar.rezoneDate(intervalStart); 
			}
			if (!intervalEnd.hasTime()) {
				intervalEnd = this.calendar.rezoneDate(intervalEnd); 
			}
		}
		start = intervalStart.clone();
		start = this.skipHiddenDays(start);
		end = intervalEnd.clone();
		end = this.skipHiddenDays(end, -1, true); 
		return {
			intervalDuration: intervalDuration,
			intervalUnit: intervalUnit,
			intervalStart: intervalStart,
			intervalEnd: intervalEnd,
			start: start,
			end: end
		};
	},
	computePrevDate: function(date) {
		return this.skipHiddenDays(
			date.clone().startOf(this.intervalUnit).subtract(this.intervalDuration), -1
		);
	},
	computeNextDate: function(date) {
		return this.skipHiddenDays(
			date.clone().startOf(this.intervalUnit).add(this.intervalDuration)
		);
	},
	computeTitle: function() {
		return this.formatRange(
			{ start: this.intervalStart, end: this.intervalEnd },
			this.opt('titleFormat') || this.computeTitleFormat(),
			this.opt('titleRangeSeparator')
		);
	},
	computeTitleFormat: function() {
		if (this.intervalUnit == 'year') {
			return 'YYYY';
		}
		else if (this.intervalUnit == 'month') {
			return this.opt('monthYearFormat'); 
		}
		else if (this.intervalDuration.as('days') > 1) {
			return 'll'; 
		}
		else {
			return 'LL'; 
		}
	},
	formatRange: function(range, formatStr, separator) {
		var end = range.end;
		if (!end.hasTime()) { 
			end = end.clone().subtract(1); 
		}
		return formatRange(range.start, end, formatStr, separator, this.opt('isRTL'));
	},
	renderView: function() {
		this.render();
		this.updateSize();
		this.initializeScroll();
		this.trigger('viewRender', this, this, this.el);
		$(document).on('mousedown', this.documentMousedownProxy);
	},
	render: function() {
	},
	destroyView: function() {
		this.unselect();
		this.destroyViewEvents();
		this.destroy();
		this.trigger('viewDestroy', this, this, this.el);
		$(document).off('mousedown', this.documentMousedownProxy);
	},
	destroy: function() {
		this.el.empty(); 
	},
	initTheming: function() {
		var tm = this.opt('theme') ? 'ui' : 'fc';
		this.widgetHeaderClass = tm + '-widget-header';
		this.widgetContentClass = tm + '-widget-content';
		this.highlightStateClass = tm + '-state-highlight';
	},
	updateSize: function(isResize) {
		if (isResize) {
			this.recordScroll();
		}
		this.updateHeight();
		this.updateWidth();
	},
	updateWidth: function() {
	},
	updateHeight: function() {
		var calendar = this.calendar; 
		this.setHeight(
			calendar.getSuggestedViewHeight(),
			calendar.isHeightAuto()
		);
	},
	setHeight: function(height, isAuto) {
	},
	computeScrollerHeight: function(totalHeight, scrollerEl) {
		var both;
		var otherHeight; 
		scrollerEl = scrollerEl || this.scrollerEl;
		both = this.el.add(scrollerEl);
		both.css({
			position: 'relative', 
			left: -1 
		});
		otherHeight = this.el.outerHeight() - scrollerEl.height(); 
		both.css({ position: '', left: '' }); 
		return totalHeight - otherHeight;
	},
	initializeScroll: function() {
	},
	recordScroll: function() {
		if (this.scrollerEl) {
			this.scrollTop = this.scrollerEl.scrollTop();
		}
	},
	restoreScroll: function() {
		if (this.scrollTop !== null) {
			this.scrollerEl.scrollTop(this.scrollTop);
		}
	},
	renderViewEvents: function(events) {
		this.renderEvents(events);
		this.eventSegEach(function(seg) {
			this.trigger('eventAfterRender', seg.event, seg.event, seg.el);
		});
		this.trigger('eventAfterAllRender');
	},
	renderEvents: function() {
	},
	destroyViewEvents: function() {
		this.eventSegEach(function(seg) {
			this.trigger('eventDestroy', seg.event, seg.event, seg.el);
		});
		this.destroyEvents();
	},
	destroyEvents: function() {
	},
	resolveEventEl: function(event, el) {
		var custom = this.trigger('eventRender', event, event, el);
		if (custom === false) { 
			el = null;
		}
		else if (custom && custom !== true) {
			el = $(custom);
		}
		return el;
	},
	showEvent: function(event) {
		this.eventSegEach(function(seg) {
			seg.el.css('visibility', '');
		}, event);
	},
	hideEvent: function(event) {
		this.eventSegEach(function(seg) {
			seg.el.css('visibility', 'hidden');
		}, event);
	},
	eventSegEach: function(func, event) {
		var segs = this.getEventSegs();
		var i;
		for (i = 0; i < segs.length; i++) {
			if (!event || segs[i].event._id === event._id) {
				func.call(this, segs[i]);
			}
		}
	},
	getEventSegs: function() {
		return [];
	},
	isEventDraggable: function(event) {
		var source = event.source || {};
		return firstDefined(
			event.startEditable,
			source.startEditable,
			this.opt('eventStartEditable'),
			event.editable,
			source.editable,
			this.opt('editable')
		);
	},
	reportEventDrop: function(event, dropLocation, el, ev) {
		var calendar = this.calendar;
		var mutateResult = calendar.mutateEvent(event, dropLocation);
		var undoFunc = function() {
			mutateResult.undo();
			calendar.reportEventChange();
		};
		this.triggerEventDrop(event, mutateResult.dateDelta, undoFunc, el, ev);
		calendar.reportEventChange(); 
	},
	triggerEventDrop: function(event, dateDelta, undoFunc, el, ev) {
		this.trigger('eventDrop', el[0], event, dateDelta, undoFunc, ev, {}); 
	},
	reportExternalDrop: function(meta, dropLocation, el, ev, ui) {
		var eventProps = meta.eventProps;
		var eventInput;
		var event;
		if (eventProps) {
			eventInput = $.extend({}, eventProps, dropLocation);
			event = this.calendar.renderEvent(eventInput, meta.stick)[0]; 
		}
		this.triggerExternalDrop(event, dropLocation, el, ev, ui);
	},
	triggerExternalDrop: function(event, dropLocation, el, ev, ui) {
		this.trigger('drop', el[0], dropLocation.start, ev, ui);
		if (event) {
			this.trigger('eventReceive', null, event); 
		}
	},
	renderDrag: function(dropLocation, seg) {
	},
	destroyDrag: function() {
	},
	isEventResizable: function(event) {
		var source = event.source || {};
		return firstDefined(
			event.durationEditable,
			source.durationEditable,
			this.opt('eventDurationEditable'),
			event.editable,
			source.editable,
			this.opt('editable')
		);
	},
	reportEventResize: function(event, newEnd, el, ev) {
		var calendar = this.calendar;
		var mutateResult = calendar.mutateEvent(event, { end: newEnd });
		var undoFunc = function() {
			mutateResult.undo();
			calendar.reportEventChange();
		};
		this.triggerEventResize(event, mutateResult.durationDelta, undoFunc, el, ev);
		calendar.reportEventChange(); 
	},
	triggerEventResize: function(event, durationDelta, undoFunc, el, ev) {
		this.trigger('eventResize', el[0], event, durationDelta, undoFunc, ev, {}); 
	},
	select: function(range, ev) {
		this.unselect(ev);
		this.renderSelection(range);
		this.reportSelection(range, ev);
	},
	renderSelection: function(range) {
	},
	reportSelection: function(range, ev) {
		this.isSelected = true;
		this.trigger('select', null, range.start, range.end, ev);
	},
	unselect: function(ev) {
		if (this.isSelected) {
			this.isSelected = false;
			this.destroySelection();
			this.trigger('unselect', null, ev);
		}
	},
	destroySelection: function() {
	},
	documentMousedown: function(ev) {
		var ignore;
		if (this.isSelected && this.opt('unselectAuto') && isPrimaryMouseButton(ev)) {
			ignore = this.opt('unselectCancel');
			if (!ignore || !$(ev.target).closest(ignore).length) {
				this.unselect(ev);
			}
		}
	},
	initHiddenDays: function() {
		var hiddenDays = this.opt('hiddenDays') || []; 
		var isHiddenDayHash = []; 
		var dayCnt = 0;
		var i;
		if (this.opt('weekends') === false) {
			hiddenDays.push(0, 6); 
		}
		for (i = 0; i < 7; i++) {
			if (
				!(isHiddenDayHash[i] = $.inArray(i, hiddenDays) !== -1)
			) {
				dayCnt++;
			}
		}
		if (!dayCnt) {
			throw 'invalid hiddenDays'; 
		}
		this.isHiddenDayHash = isHiddenDayHash;
	},
	isHiddenDay: function(day) {
		if (moment.isMoment(day)) {
			day = day.day();
		}
		return this.isHiddenDayHash[day];
	},
	skipHiddenDays: function(date, inc, isExclusive) {
		var out = date.clone();
		inc = inc || 1;
		while (
			this.isHiddenDayHash[(out.day() + (isExclusive ? inc : 0) + 7) % 7]
		) {
			out.add(inc, 'days');
		}
		return out;
	},
	computeDayRange: function(range) {
		var startDay = range.start.clone().stripTime(); 
		var end = range.end;
		var endDay = null;
		var endTimeMS;
		if (end) {
			endDay = end.clone().stripTime(); 
			endTimeMS = +end.time(); 
			if (endTimeMS && endTimeMS >= this.nextDayThreshold) {
				endDay.add(1, 'days');
			}
		}
		if (!end || endDay <= startDay) {
			endDay = startDay.clone().add(1, 'days');
		}
		return { start: startDay, end: endDay };
	},
	isMultiDayEvent: function(event) {
		var range = this.computeDayRange(event); 
		return range.end.diff(range.start, 'days') > 1;
	}
});
;;
function Calendar(element, instanceOptions) {
	var t = this;
	instanceOptions = instanceOptions || {};
	var options = mergeOptions({}, defaults, instanceOptions);
	var langOptions;
	if (options.lang in langOptionHash) {
		langOptions = langOptionHash[options.lang];
	}
	else {
		langOptions = langOptionHash[defaults.lang];
	}
	if (langOptions) { 
		options = mergeOptions({}, defaults, langOptions, instanceOptions);
	}
	if (options.isRTL) { 
		options = mergeOptions({}, defaults, rtlDefaults, langOptions || {}, instanceOptions);
	}
	t.options = options;
	t.render = render;
	t.destroy = destroy;
	t.refetchEvents = refetchEvents;
	t.reportEvents = reportEvents;
	t.reportEventChange = reportEventChange;
	t.rerenderEvents = renderEvents; 
	t.changeView = changeView;
	t.select = select;
	t.unselect = unselect;
	t.prev = prev;
	t.next = next;
	t.prevYear = prevYear;
	t.nextYear = nextYear;
	t.today = today;
	t.gotoDate = gotoDate;
	t.incrementDate = incrementDate;
	t.zoomTo = zoomTo;
	t.getDate = getDate;
	t.getCalendar = getCalendar;
	t.getView = getView;
	t.option = option;
	t.trigger = trigger;
	t.isValidViewType = isValidViewType;
	t.getViewButtonText = getViewButtonText;
	var localeData = createObject( 
		getMomentLocaleData(options.lang) 
	);
	if (options.monthNames) {
		localeData._months = options.monthNames;
	}
	if (options.monthNamesShort) {
		localeData._monthsShort = options.monthNamesShort;
	}
	if (options.dayNames) {
		localeData._weekdays = options.dayNames;
	}
	if (options.dayNamesShort) {
		localeData._weekdaysShort = options.dayNamesShort;
	}
	if (options.firstDay != null) {
		var _week = createObject(localeData._week); 
		_week.dow = options.firstDay;
		localeData._week = _week;
	}
	t.defaultAllDayEventDuration = moment.duration(options.defaultAllDayEventDuration);
	t.defaultTimedEventDuration = moment.duration(options.defaultTimedEventDuration);
	t.moment = function() {
		var mom;
		if (options.timezone === 'local') {
			mom = fc.moment.apply(null, arguments);
			if (mom.hasTime()) { 
				mom.local();
			}
		}
		else if (options.timezone === 'UTC') {
			mom = fc.moment.utc.apply(null, arguments); 
		}
		else {
			mom = fc.moment.parseZone.apply(null, arguments); 
		}
		if ('_locale' in mom) { 
			mom._locale = localeData;
		}
		else { 
			mom._lang = localeData;
		}
		return mom;
	};
	t.getIsAmbigTimezone = function() {
		return options.timezone !== 'local' && options.timezone !== 'UTC';
	};
	t.rezoneDate = function(date) {
		return t.moment(date.toArray());
	};
	t.getNow = function() {
		var now = options.now;
		if (typeof now === 'function') {
			now = now();
		}
		return t.moment(now);
	};
	t.calculateWeekNumber = function(mom) {
		var calc = options.weekNumberCalculation;
		if (typeof calc === 'function') {
			return calc(mom);
		}
		else if (calc === 'local') {
			return mom.week();
		}
		else if (calc.toUpperCase() === 'ISO') {
			return mom.isoWeek();
		}
	};
	t.getEventEnd = function(event) {
		if (event.end) {
			return event.end.clone();
		}
		else {
			return t.getDefaultEventEnd(event.allDay, event.start);
		}
	};
	t.getDefaultEventEnd = function(allDay, start) { 
		var end = start.clone();
		if (allDay) {
			end.stripTime().add(t.defaultAllDayEventDuration);
		}
		else {
			end.add(t.defaultTimedEventDuration);
		}
		if (t.getIsAmbigTimezone()) {
			end.stripZone(); 
		}
		return end;
	};
	function humanizeDuration(duration) {
		return (duration.locale || duration.lang).call(duration, options.lang) 
			.humanize();
	}
	EventManager.call(t, options);
	var isFetchNeeded = t.isFetchNeeded;
	var fetchEvents = t.fetchEvents;
	var _element = element[0];
	var header;
	var headerElement;
	var content;
	var tm; 
	var viewSpecCache = {};
	var currentView;
	var suggestedViewHeight;
	var windowResizeProxy; 
	var ignoreWindowResize = 0;
	var date;
	var events = [];
	if (options.defaultDate != null) {
		date = t.moment(options.defaultDate);
	}
	else {
		date = t.getNow();
	}
	function render(inc) {
		if (!content) {
			initialRender();
		}
		else if (elementVisible()) {
			calcSize();
			renderView(inc);
		}
	}
	function initialRender() {
		tm = options.theme ? 'ui' : 'fc';
		element.addClass('fc');
		if (options.isRTL) {
			element.addClass('fc-rtl');
		}
		else {
			element.addClass('fc-ltr');
		}
		if (options.theme) {
			element.addClass('ui-widget');
		}
		else {
			element.addClass('fc-unthemed');
		}
		content = $("<div class='fc-view-container'/>").prependTo(element);
		header = new Header(t, options);
		headerElement = header.render();
		if (headerElement) {
			element.prepend(headerElement);
		}
		changeView(options.defaultView);
		if (options.handleWindowResize) {
			windowResizeProxy = debounce(windowResize, options.windowResizeDelay); 
			$(window).resize(windowResizeProxy);
		}
	}
	function destroy() {
		if (currentView) {
			currentView.destroyView();
		}
		header.destroy();
		content.remove();
		element.removeClass('fc fc-ltr fc-rtl fc-unthemed ui-widget');
		$(window).unbind('resize', windowResizeProxy);
	}
	function elementVisible() {
		return element.is(':visible');
	}
	function changeView(viewType) {
		renderView(0, viewType);
	}
	function renderView(delta, viewType) {
		ignoreWindowResize++;
		if (currentView && viewType && currentView.type !== viewType) {
			header.deactivateButton(currentView.type);
			freezeContentHeight(); 
			if (currentView.start) { 
				currentView.destroyView();
			}
			currentView.el.remove();
			currentView = null;
		}
		if (!currentView && viewType) {
			currentView = instantiateView(viewType);
			currentView.el =  $("<div class='fc-view fc-" + viewType + "-view' />").appendTo(content);
			header.activateButton(viewType);
		}
		if (currentView) {
			if (delta < 0) {
				date = currentView.computePrevDate(date);
			}
			else if (delta > 0) {
				date = currentView.computeNextDate(date);
			}
			if (
				!currentView.start || 
				delta || 
				!date.isWithin(currentView.intervalStart, currentView.intervalEnd) 
			) {
				if (elementVisible()) {
					freezeContentHeight();
					if (currentView.start) { 
						currentView.destroyView();
					}
					currentView.setDate(date);
					currentView.renderView();
					unfreezeContentHeight();
					updateTitle();
					updateTodayButton();
					getAndRenderEvents();
				}
			}
		}
		unfreezeContentHeight(); 
		ignoreWindowResize--;
	}
	function instantiateView(viewType) {
		var spec = getViewSpec(viewType);
		return new spec['class'](t, spec.options, viewType);
	}
	function getViewSpec(requestedViewType) {
		var allDefaultButtonText = options.defaultButtonText || {};
		var allButtonText = options.buttonText || {};
		var hash = options.views || {}; 
		var viewType = requestedViewType;
		var viewOptionsChain = [];
		var viewOptions;
		var viewClass;
		var duration, unit, unitIsSingle = false;
		var buttonText;
		if (viewSpecCache[requestedViewType]) {
			return viewSpecCache[requestedViewType];
		}
		function processSpecInput(input) {
			if (typeof input === 'function') {
				viewClass = input;
			}
			else if (typeof input === 'object') {
				$.extend(viewOptions, input);
			}
		}
		while (viewType && !viewClass) {
			viewOptions = {}; 
			processSpecInput(fcViews[viewType]); 
			processSpecInput(hash[viewType]); 
			viewOptionsChain.unshift(viewOptions); 
			viewType = viewOptions.type;
		}
		viewOptionsChain.unshift({}); 
		viewOptions = $.extend.apply($, viewOptionsChain); 
		if (viewClass) {
			duration = viewOptions.duration || viewClass.duration;
			if (duration) {
				duration = moment.duration(duration);
				unit = computeIntervalUnit(duration);
				unitIsSingle = computeIntervalAs(unit, duration) === 1;
			}
			if (unitIsSingle && hash[unit]) {
				viewOptions = $.extend({}, hash[unit], viewOptions); 
			}
			buttonText =
				allButtonText[requestedViewType] || 
				(unitIsSingle ? allButtonText[unit] : null) || 
				allDefaultButtonText[requestedViewType] || 
				(unitIsSingle ? allDefaultButtonText[unit] : null) || 
				viewOptions.buttonText ||
				viewClass.buttonText ||
				(duration ? humanizeDuration(duration) : null) ||
				requestedViewType;
			return (viewSpecCache[requestedViewType] = {
				'class': viewClass,
				options: viewOptions,
				buttonText: buttonText
			});
		}
	}
	function isValidViewType(viewType) {
		return Boolean(getViewSpec(viewType));
	}
	function getViewButtonText(viewType) {
		var spec = getViewSpec(viewType);
		if (spec) {
			return spec.buttonText;
		}
	}
	t.getSuggestedViewHeight = function() {
		if (suggestedViewHeight === undefined) {
			calcSize();
		}
		return suggestedViewHeight;
	};
	t.isHeightAuto = function() {
		return options.contentHeight === 'auto' || options.height === 'auto';
	};
	function updateSize(shouldRecalc) {
		if (elementVisible()) {
			if (shouldRecalc) {
				_calcSize();
			}
			ignoreWindowResize++;
			currentView.updateSize(true); 
			ignoreWindowResize--;
			return true; 
		}
	}
	function calcSize() {
		if (elementVisible()) {
			_calcSize();
		}
	}
	function _calcSize() { 
		if (typeof options.contentHeight === 'number') { 
			suggestedViewHeight = options.contentHeight;
		}
		else if (typeof options.height === 'number') { 
			suggestedViewHeight = options.height - (headerElement ? headerElement.outerHeight(true) : 0);
		}
		else {
			suggestedViewHeight = Math.round(content.width() / Math.max(options.aspectRatio, .5));
		}
	}
	function windowResize(ev) {
		if (
			!ignoreWindowResize &&
			ev.target === window && 
			currentView.start 
		) {
			if (updateSize(true)) {
				currentView.trigger('windowResize', _element);
			}
		}
	}
	function refetchEvents() { 
		destroyEvents(); 
		fetchAndRenderEvents();
	}
	function renderEvents() { 
		if (elementVisible()) {
			freezeContentHeight();
			currentView.destroyViewEvents(); 
			currentView.renderViewEvents(events);
			unfreezeContentHeight();
		}
	}
	function destroyEvents() {
		freezeContentHeight();
		currentView.destroyViewEvents();
		unfreezeContentHeight();
	}
	function getAndRenderEvents() {
		if (!options.lazyFetching || isFetchNeeded(currentView.start, currentView.end)) {
			fetchAndRenderEvents();
		}
		else {
			renderEvents();
		}
	}
	function fetchAndRenderEvents() {
		fetchEvents(currentView.start, currentView.end);
	}
	function reportEvents(_events) {
		events = _events;
		renderEvents();
	}
	function reportEventChange() {
		renderEvents();
	}
	function updateTitle() {
		header.updateTitle(currentView.computeTitle());
	}
	function updateTodayButton() {
		var now = t.getNow();
		if (now.isWithin(currentView.intervalStart, currentView.intervalEnd)) {
			header.disableButton('today');
		}
		else {
			header.enableButton('today');
		}
	}
	function select(start, end) {
		start = t.moment(start);
		if (end) {
			end = t.moment(end);
		}
		else if (start.hasTime()) {
			end = start.clone().add(t.defaultTimedEventDuration);
		}
		else {
			end = start.clone().add(t.defaultAllDayEventDuration);
		}
		currentView.select({ start: start, end: end }); 
	}
	function unselect() { 
		if (currentView) {
			currentView.unselect();
		}
	}
	function prev() {
		renderView(-1);
	}
	function next() {
		renderView(1);
	}
	function prevYear() {
		date.add(-1, 'years');
		renderView();
	}
	function nextYear() {
		date.add(1, 'years');
		renderView();
	}
	function today() {
		date = t.getNow();
		renderView();
	}
	function gotoDate(dateInput) {
		date = t.moment(dateInput);
		renderView();
	}
	function incrementDate(delta) {
		date.add(moment.duration(delta));
		renderView();
	}
	function zoomTo(newDate, viewType) {
		var viewStr;
		var match;
		if (!viewType || !isValidViewType(viewType)) { 
			viewType = viewType || 'day';
			viewStr = header.getViewsWithButtons().join(' '); 
			match = viewStr.match(new RegExp('\\w+' + capitaliseFirstLetter(viewType)));
			if (!match) {
				match = viewStr.match(/\w+Day/);
			}
			viewType = match ? match[0] : 'agendaDay'; 
		}
		date = newDate;
		changeView(viewType);
	}
	function getDate() {
		return date.clone();
	}
	function freezeContentHeight() {
		content.css({
			width: '100%',
			height: content.height(),
			overflow: 'hidden'
		});
	}
	function unfreezeContentHeight() {
		content.css({
			width: '',
			height: '',
			overflow: ''
		});
	}
	function getCalendar() {
		return t;
	}
	function getView() {
		return currentView;
	}
	function option(name, value) {
		if (value === undefined) {
			return options[name];
		}
		if (name == 'height' || name == 'contentHeight' || name == 'aspectRatio') {
			options[name] = value;
			updateSize(true); 
		}
	}
	function trigger(name, thisObj) {
		if (options[name]) {
			return options[name].apply(
				thisObj || _element,
				Array.prototype.slice.call(arguments, 2)
			);
		}
	}
}
;;
function Header(calendar, options) {
	var t = this;
	t.render = render;
	t.destroy = destroy;
	t.updateTitle = updateTitle;
	t.activateButton = activateButton;
	t.deactivateButton = deactivateButton;
	t.disableButton = disableButton;
	t.enableButton = enableButton;
	t.getViewsWithButtons = getViewsWithButtons;
	var el = $();
	var viewsWithButtons = [];
	var tm;
	function render() {
		var sections = options.header;
		tm = options.theme ? 'ui' : 'fc';
		if (sections) {
			el = $("<div class='fc-toolbar'/>")
				.append(renderSection('left'))
				.append(renderSection('right'))
				.append(renderSection('center'))
				.append('<div class="fc-clear"/>');
			return el;
		}
	}
	function destroy() {
		el.remove();
	}
	function renderSection(position) {
		var sectionEl = $('<div class="fc-' + position + '"/>');
		var buttonStr = options.header[position];
		if (buttonStr) {
			$.each(buttonStr.split(' '), function(i) {
				var groupChildren = $();
				var isOnlyButtons = true;
				var groupEl;
				$.each(this.split(','), function(j, buttonName) {
					var buttonClick;
					var themeIcon;
					var normalIcon;
					var defaultText;
					var viewText; 
					var customText;
					var innerHtml;
					var classes;
					var button;
					if (buttonName == 'title') {
						groupChildren = groupChildren.add($('<h2>&nbsp;</h2>')); 
						isOnlyButtons = false;
					}
					else {
						if (calendar[buttonName]) { 
							buttonClick = function() {
								calendar[buttonName]();
							};
						}
						else if (calendar.isValidViewType(buttonName)) { 
							buttonClick = function() {
								calendar.changeView(buttonName);
							};
							viewsWithButtons.push(buttonName);
							viewText = calendar.getViewButtonText(buttonName);
						}
						if (buttonClick) {
							themeIcon = smartProperty(options.themeButtonIcons, buttonName);
							normalIcon = smartProperty(options.buttonIcons, buttonName);
							defaultText = smartProperty(options.defaultButtonText, buttonName); 
							customText = smartProperty(options.buttonText, buttonName);
							if (viewText || customText) {
								innerHtml = htmlEscape(viewText || customText);
							}
							else if (themeIcon && options.theme) {
								innerHtml = "<span class='ui-icon ui-icon-" + themeIcon + "'></span>";
							}
							else if (normalIcon && !options.theme) {
								innerHtml = "<span class='fc-icon fc-icon-" + normalIcon + "'></span>";
							}
							else {
								innerHtml = htmlEscape(defaultText || buttonName);
							}
							classes = [
								'fc-' + buttonName + '-button',
								tm + '-button',
								tm + '-state-default'
							];
							button = $( 
								'<button type="button" class="' + classes.join(' ') + '">' +
									innerHtml +
								'</button>'
								)
								.click(function() {
									if (!button.hasClass(tm + '-state-disabled')) {
										buttonClick();
										if (
											button.hasClass(tm + '-state-active') ||
											button.hasClass(tm + '-state-disabled')
										) {
											button.removeClass(tm + '-state-hover');
										}
									}
								})
								.mousedown(function() {
									button
										.not('.' + tm + '-state-active')
										.not('.' + tm + '-state-disabled')
										.addClass(tm + '-state-down');
								})
								.mouseup(function() {
									button.removeClass(tm + '-state-down');
								})
								.hover(
									function() {
										button
											.not('.' + tm + '-state-active')
											.not('.' + tm + '-state-disabled')
											.addClass(tm + '-state-hover');
									},
									function() {
										button
											.removeClass(tm + '-state-hover')
											.removeClass(tm + '-state-down'); 
									}
								);
							groupChildren = groupChildren.add(button);
						}
					}
				});
				if (isOnlyButtons) {
					groupChildren
						.first().addClass(tm + '-corner-left').end()
						.last().addClass(tm + '-corner-right').end();
				}
				if (groupChildren.length > 1) {
					groupEl = $('<div/>');
					if (isOnlyButtons) {
						groupEl.addClass('fc-button-group');
					}
					groupEl.append(groupChildren);
					sectionEl.append(groupEl);
				}
				else {
					sectionEl.append(groupChildren); 
				}
			});
		}
		return sectionEl;
	}
	function updateTitle(text) {
		el.find('h2').text(text);
	}
	function activateButton(buttonName) {
		el.find('.fc-' + buttonName + '-button')
			.addClass(tm + '-state-active');
	}
	function deactivateButton(buttonName) {
		el.find('.fc-' + buttonName + '-button')
			.removeClass(tm + '-state-active');
	}
	function disableButton(buttonName) {
		el.find('.fc-' + buttonName + '-button')
			.attr('disabled', 'disabled')
			.addClass(tm + '-state-disabled');
	}
	function enableButton(buttonName) {
		el.find('.fc-' + buttonName + '-button')
			.removeAttr('disabled')
			.removeClass(tm + '-state-disabled');
	}
	function getViewsWithButtons() {
		return viewsWithButtons;
	}
}
;;
fc.sourceNormalizers = [];
fc.sourceFetchers = [];
var ajaxDefaults = {
	dataType: 'json',
	cache: false
};
var eventGUID = 1;
function EventManager(options) { 
	var t = this;
	t.isFetchNeeded = isFetchNeeded;
	t.fetchEvents = fetchEvents;
	t.addEventSource = addEventSource;
	t.removeEventSource = removeEventSource;
	t.updateEvent = updateEvent;
	t.renderEvent = renderEvent;
	t.removeEvents = removeEvents;
	t.clientEvents = clientEvents;
	t.mutateEvent = mutateEvent;
	t.normalizeEventDateProps = normalizeEventDateProps;
	t.ensureVisibleEventRange = ensureVisibleEventRange;
	var trigger = t.trigger;
	var getView = t.getView;
	var reportEvents = t.reportEvents;
	var stickySource = { events: [] };
	var sources = [ stickySource ];
	var rangeStart, rangeEnd;
	var currentFetchID = 0;
	var pendingSourceCnt = 0;
	var loadingLevel = 0;
	var cache = []; 
	$.each(
		(options.events ? [ options.events ] : []).concat(options.eventSources || []),
		function(i, sourceInput) {
			var source = buildEventSource(sourceInput);
			if (source) {
				sources.push(source);
			}
		}
	);
	function isFetchNeeded(start, end) {
		return !rangeStart || 
			start.clone().stripZone() < rangeStart.clone().stripZone() ||
			end.clone().stripZone() > rangeEnd.clone().stripZone();
	}
	function fetchEvents(start, end) {
		rangeStart = start;
		rangeEnd = end;
		cache = [];
		var fetchID = ++currentFetchID;
		var len = sources.length;
		pendingSourceCnt = len;
		for (var i=0; i<len; i++) {
			fetchEventSource(sources[i], fetchID);
		}
	}
	function fetchEventSource(source, fetchID) {
		_fetchEventSource(source, function(eventInputs) {
			var isArraySource = $.isArray(source.events);
			var i, eventInput;
			var abstractEvent;
			if (fetchID == currentFetchID) {
				if (eventInputs) {
					for (i = 0; i < eventInputs.length; i++) {
						eventInput = eventInputs[i];
						if (isArraySource) { 
							abstractEvent = eventInput;
						}
						else {
							abstractEvent = buildEventFromInput(eventInput, source);
						}
						if (abstractEvent) { 
							cache.push.apply(
								cache,
								expandEvent(abstractEvent) 
							);
						}
					}
				}
				pendingSourceCnt--;
				if (!pendingSourceCnt) {
					reportEvents(cache);
				}
			}
		});
	}
	function _fetchEventSource(source, callback) {
		var i;
		var fetchers = fc.sourceFetchers;
		var res;
		for (i=0; i<fetchers.length; i++) {
			res = fetchers[i].call(
				t, 
				source,
				rangeStart.clone(),
				rangeEnd.clone(),
				options.timezone,
				callback
			);
			if (res === true) {
				return;
			}
			else if (typeof res == 'object') {
				_fetchEventSource(res, callback);
				return;
			}
		}
		var events = source.events;
		if (events) {
			if ($.isFunction(events)) {
				pushLoading();
				events.call(
					t, 
					rangeStart.clone(),
					rangeEnd.clone(),
					options.timezone,
					function(events) {
						callback(events);
						popLoading();
					}
				);
			}
			else if ($.isArray(events)) {
				callback(events);
			}
			else {
				callback();
			}
		}else{
			var url = source.url;
			if (url) {
				var success = source.success;
				var error = source.error;
				var complete = source.complete;
				var customData;
				if ($.isFunction(source.data)) {
					customData = source.data();
				}
				else {
					customData = source.data;
				}
				var data = $.extend({}, customData || {});
				var startParam = firstDefined(source.startParam, options.startParam);
				var endParam = firstDefined(source.endParam, options.endParam);
				var timezoneParam = firstDefined(source.timezoneParam, options.timezoneParam);
				if (startParam) {
					data[startParam] = rangeStart.format();
				}
				if (endParam) {
					data[endParam] = rangeEnd.format();
				}
				if (options.timezone && options.timezone != 'local') {
					data[timezoneParam] = options.timezone;
				}
				pushLoading();
				$.ajax($.extend({}, ajaxDefaults, source, {
					data: data,
					success: function(events) {
						events = events || [];
						var res = applyAll(success, this, arguments);
						if ($.isArray(res)) {
							events = res;
						}
						callback(events);
					},
					error: function() {
						applyAll(error, this, arguments);
						callback();
					},
					complete: function() {
						applyAll(complete, this, arguments);
						popLoading();
					}
				}));
			}else{
				callback();
			}
		}
	}
	function addEventSource(sourceInput) {
		var source = buildEventSource(sourceInput);
		if (source) {
			sources.push(source);
			pendingSourceCnt++;
			fetchEventSource(source, currentFetchID); 
		}
	}
	function buildEventSource(sourceInput) { 
		var normalizers = fc.sourceNormalizers;
		var source;
		var i;
		if ($.isFunction(sourceInput) || $.isArray(sourceInput)) {
			source = { events: sourceInput };
		}
		else if (typeof sourceInput === 'string') {
			source = { url: sourceInput };
		}
		else if (typeof sourceInput === 'object') {
			source = $.extend({}, sourceInput); 
		}
		if (source) {
			if (source.className) {
				if (typeof source.className === 'string') {
					source.className = source.className.split(/\s+/);
				}
			}
			else {
				source.className = [];
			}
			if ($.isArray(source.events)) {
				source.origArray = source.events; 
				source.events = $.map(source.events, function(eventInput) {
					return buildEventFromInput(eventInput, source);
				});
			}
			for (i=0; i<normalizers.length; i++) {
				normalizers[i].call(t, source);
			}
			return source;
		}
	}
	function removeEventSource(source) {
		sources = $.grep(sources, function(src) {
			return !isSourcesEqual(src, source);
		});
		cache = $.grep(cache, function(e) {
			return !isSourcesEqual(e.source, source);
		});
		reportEvents(cache);
	}
	function isSourcesEqual(source1, source2) {
		return source1 && source2 && getSourcePrimitive(source1) == getSourcePrimitive(source2);
	}
	function getSourcePrimitive(source) {
		return (
			(typeof source === 'object') ? 
				(source.origArray || source.googleCalendarId || source.url || source.events) : 
				null
		) ||
		source; 
	}
	function updateEvent(event) {
		event.start = t.moment(event.start);
		if (event.end) {
			event.end = t.moment(event.end);
		}
		else {
			event.end = null;
		}
		mutateEvent(event, getMiscEventProps(event)); 
		reportEvents(cache); 
	}
	function getMiscEventProps(event) {
		var props = {};
		$.each(event, function(name, val) {
			if (isMiscEventPropName(name)) {
				if (val !== undefined && isAtomic(val)) { 
					props[name] = val;
				}
			}
		});
		return props;
	}
	function isMiscEventPropName(name) {
		return !/^_|^(id|allDay|start|end)$/.test(name);
	}
	function renderEvent(eventInput, stick) {
		var abstractEvent = buildEventFromInput(eventInput);
		var events;
		var i, event;
		if (abstractEvent) { 
			events = expandEvent(abstractEvent);
			for (i = 0; i < events.length; i++) {
				event = events[i];
				if (!event.source) {
					if (stick) {
						stickySource.events.push(event);
						event.source = stickySource;
					}
					cache.push(event);
				}
			}
			reportEvents(cache);
			return events;
		}
		return [];
	}
	function removeEvents(filter) {
		var eventID;
		var i;
		if (filter == null) { 
			filter = function() { return true; }; 
		}
		else if (!$.isFunction(filter)) { 
			eventID = filter + '';
			filter = function(event) {
				return event._id == eventID;
			};
		}
		cache = $.grep(cache, filter, true); 
		for (i=0; i<sources.length; i++) {
			if ($.isArray(sources[i].events)) {
				sources[i].events = $.grep(sources[i].events, filter, true);
			}
		}
		reportEvents(cache);
	}
	function clientEvents(filter) {
		if ($.isFunction(filter)) {
			return $.grep(cache, filter);
		}
		else if (filter != null) { 
			filter += '';
			return $.grep(cache, function(e) {
				return e._id == filter;
			});
		}
		return cache; 
	}
	function pushLoading() {
		if (!(loadingLevel++)) {
			trigger('loading', null, true, getView());
		}
	}
	function popLoading() {
		if (!(--loadingLevel)) {
			trigger('loading', null, false, getView());
		}
	}
	function buildEventFromInput(input, source) {
		var out = {};
		var start, end;
		var allDay;
		if (options.eventDataTransform) {
			input = options.eventDataTransform(input);
		}
		if (source && source.eventDataTransform) {
			input = source.eventDataTransform(input);
		}
		$.extend(out, input);
		if (source) {
			out.source = source;
		}
		out._id = input._id || (input.id === undefined ? '_fc' + eventGUID++ : input.id + '');
		if (input.className) {
			if (typeof input.className == 'string') {
				out.className = input.className.split(/\s+/);
			}
			else { 
				out.className = input.className;
			}
		}
		else {
			out.className = [];
		}
		start = input.start || input.date; 
		end = input.end;
		if (isTimeString(start)) {
			start = moment.duration(start);
		}
		if (isTimeString(end)) {
			end = moment.duration(end);
		}
		if (input.dow || moment.isDuration(start) || moment.isDuration(end)) {
			out.start = start ? moment.duration(start) : null; 
			out.end = end ? moment.duration(end) : null; 
			out._recurring = true; 
		}
		else {
			if (start) {
				start = t.moment(start);
				if (!start.isValid()) {
					return false;
				}
			}
			if (end) {
				end = t.moment(end);
				if (!end.isValid()) {
					end = null; 
				}
			}
			allDay = input.allDay;
			if (allDay === undefined) { 
				allDay = firstDefined(
					source ? source.allDayDefault : undefined,
					options.allDayDefault
				);
			}
			assignDatesToEvent(start, end, allDay, out);
		}
		return out;
	}
	function assignDatesToEvent(start, end, allDay, event) {
		event.start = start;
		event.end = end;
		event.allDay = allDay;
		normalizeEventDateProps(event);
		backupEventDates(event);
	}
	function normalizeEventDateProps(props) {
		if (props.allDay == null) {
			props.allDay = !(props.start.hasTime() || (props.end && props.end.hasTime()));
		}
		if (props.allDay) {
			props.start.stripTime();
			if (props.end) {
				props.end.stripTime();
			}
		}
		else {
			if (!props.start.hasTime()) {
				props.start = t.rezoneDate(props.start); 
			}
			if (props.end && !props.end.hasTime()) {
				props.end = t.rezoneDate(props.end); 
			}
		}
		if (props.end && !props.end.isAfter(props.start)) {
			props.end = null;
		}
		if (!props.end) {
			if (options.forceEventDuration) {
				props.end = t.getDefaultEventEnd(props.allDay, props.start);
			}
			else {
				props.end = null;
			}
		}
	}
	function ensureVisibleEventRange(range) {
		var allDay;
		if (!range.end) {
			allDay = range.allDay; 
			if (allDay == null) {
				allDay = !range.start.hasTime();
			}
			range = {
				start: range.start,
				end: t.getDefaultEventEnd(allDay, range.start)
			};
		}
		return range;
	}
	function expandEvent(abstractEvent, _rangeStart, _rangeEnd) {
		var events = [];
		var dowHash;
		var dow;
		var i;
		var date;
		var startTime, endTime;
		var start, end;
		var event;
		_rangeStart = _rangeStart || rangeStart;
		_rangeEnd = _rangeEnd || rangeEnd;
		if (abstractEvent) {
			if (abstractEvent._recurring) {
				if ((dow = abstractEvent.dow)) {
					dowHash = {};
					for (i = 0; i < dow.length; i++) {
						dowHash[dow[i]] = true;
					}
				}
				date = _rangeStart.clone().stripTime(); 
				while (date.isBefore(_rangeEnd)) {
					if (!dowHash || dowHash[date.day()]) { 
						startTime = abstractEvent.start; 
						endTime = abstractEvent.end; 
						start = date.clone();
						end = null;
						if (startTime) {
							start = start.time(startTime);
						}
						if (endTime) {
							end = date.clone().time(endTime);
						}
						event = $.extend({}, abstractEvent); 
						assignDatesToEvent(
							start, end,
							!startTime && !endTime, 
							event
						);
						events.push(event);
					}
					date.add(1, 'days');
				}
			}
			else {
				events.push(abstractEvent); 
			}
		}
		return events;
	}
	function mutateEvent(event, props) {
		var miscProps = {};
		var clearEnd;
		var dateDelta;
		var durationDelta;
		var undoFunc;
		props = props || {};
		if (!props.start) {
			props.start = event.start.clone();
		}
		if (props.end === undefined) {
			props.end = event.end ? event.end.clone() : null;
		}
		if (props.allDay == null) { 
			props.allDay = event.allDay;
		}
		normalizeEventDateProps(props); 
		clearEnd = event._end !== null && props.end === null;
		if (props.allDay) {
			dateDelta = diffDay(props.start, event._start); 
		}
		else {
			dateDelta = diffDayTime(props.start, event._start);
		}
		if (!clearEnd && props.end) {
			durationDelta = diffDayTime(
				props.end,
				props.start
			).subtract(diffDayTime(
				event._end || t.getDefaultEventEnd(event._allDay, event._start),
				event._start
			));
		}
		$.each(props, function(name, val) {
			if (isMiscEventPropName(name)) {
				if (val !== undefined) {
					miscProps[name] = val;
				}
			}
		});
		undoFunc = mutateEvents(
			clientEvents(event._id), 
			clearEnd,
			props.allDay,
			dateDelta,
			durationDelta,
			miscProps
		);
		return {
			dateDelta: dateDelta,
			durationDelta: durationDelta,
			undo: undoFunc
		};
	}
	function mutateEvents(events, clearEnd, allDay, dateDelta, durationDelta, miscProps) {
		var isAmbigTimezone = t.getIsAmbigTimezone();
		var undoFunctions = [];
		if (dateDelta && !dateDelta.valueOf()) { dateDelta = null; }
		if (durationDelta && !durationDelta.valueOf()) { durationDelta = null; }
		$.each(events, function(i, event) {
			var oldProps;
			var newProps;
			oldProps = {
				start: event.start.clone(),
				end: event.end ? event.end.clone() : null,
				allDay: event.allDay
			};
			$.each(miscProps, function(name) {
				oldProps[name] = event[name];
			});
			newProps = {
				start: event._start,
				end: event._end,
				allDay: event._allDay
			};
			if (clearEnd) {
				newProps.end = null;
			}
			newProps.allDay = allDay;
			normalizeEventDateProps(newProps); 
			if (dateDelta) {
				newProps.start.add(dateDelta);
				if (newProps.end) {
					newProps.end.add(dateDelta);
				}
			}
			if (durationDelta) {
				if (!newProps.end) {
					newProps.end = t.getDefaultEventEnd(newProps.allDay, newProps.start);
				}
				newProps.end.add(durationDelta);
			}
			if (
				isAmbigTimezone &&
				!newProps.allDay &&
				(dateDelta || durationDelta)
			) {
				newProps.start.stripZone();
				if (newProps.end) {
					newProps.end.stripZone();
				}
			}
			$.extend(event, miscProps, newProps); 
			backupEventDates(event); 
			undoFunctions.push(function() {
				$.extend(event, oldProps);
				backupEventDates(event); 
			});
		});
		return function() {
			for (var i = 0; i < undoFunctions.length; i++) {
				undoFunctions[i]();
			}
		};
	}
	t.getBusinessHoursEvents = getBusinessHoursEvents;
	function getBusinessHoursEvents() {
		var optionVal = options.businessHours;
		var defaultVal = {
			className: 'fc-nonbusiness',
			start: '09:00',
			end: '17:00',
			dow: [ 1, 2, 3, 4, 5 ], 
			rendering: 'inverse-background'
		};
		var view = t.getView();
		var eventInput;
		if (optionVal) {
			if (typeof optionVal === 'object') {
				eventInput = $.extend({}, defaultVal, optionVal);
			}
			else {
				eventInput = defaultVal;
			}
		}
		if (eventInput) {
			return expandEvent(
				buildEventFromInput(eventInput),
				view.start,
				view.end
			);
		}
		return [];
	}
	t.isEventRangeAllowed = isEventRangeAllowed;
	t.isSelectionRangeAllowed = isSelectionRangeAllowed;
	t.isExternalDropRangeAllowed = isExternalDropRangeAllowed;
	function isEventRangeAllowed(range, event) {
		var source = event.source || {};
		var constraint = firstDefined(
			event.constraint,
			source.constraint,
			options.eventConstraint
		);
		var overlap = firstDefined(
			event.overlap,
			source.overlap,
			options.eventOverlap
		);
		range = ensureVisibleEventRange(range); 
		return isRangeAllowed(range, constraint, overlap, event);
	}
	function isSelectionRangeAllowed(range) {
		return isRangeAllowed(range, options.selectConstraint, options.selectOverlap);
	}
	function isExternalDropRangeAllowed(range, eventProps) {
		var eventInput;
		var event;
		if (eventProps) {
			eventInput = $.extend({}, eventProps, range);
			event = expandEvent(buildEventFromInput(eventInput))[0];
		}
		if (event) {
			return isEventRangeAllowed(range, event);
		}
		else { 
			range = ensureVisibleEventRange(range); 
			return isSelectionRangeAllowed(range);
		}
	}
	function isRangeAllowed(range, constraint, overlap, event) {
		var constraintEvents;
		var anyContainment;
		var i, otherEvent;
		var otherOverlap;
		range = {
			start: range.start.clone().stripZone(),
			end: range.end.clone().stripZone()
		};
		if (constraint != null) {
			constraintEvents = constraintToEvents(constraint);
			anyContainment = false;
			for (i = 0; i < constraintEvents.length; i++) {
				if (eventContainsRange(constraintEvents[i], range)) {
					anyContainment = true;
					break;
				}
			}
			if (!anyContainment) {
				return false;
			}
		}
		for (i = 0; i < cache.length; i++) { 
			otherEvent = cache[i];
			if (event && event._id === otherEvent._id) {
				continue;
			}
			if (eventIntersectsRange(otherEvent, range)) {
				if (overlap === false) {
					return false;
				}
				else if (typeof overlap === 'function' && !overlap(otherEvent, event)) {
					return false;
				}
				if (event) {
					otherOverlap = firstDefined(
						otherEvent.overlap,
						(otherEvent.source || {}).overlap
					);
					if (otherOverlap === false) {
						return false;
					}
					if (typeof otherOverlap === 'function' && !otherOverlap(event, otherEvent)) {
						return false;
					}
				}
			}
		}
		return true;
	}
	function constraintToEvents(constraintInput) {
		if (constraintInput === 'businessHours') {
			return getBusinessHoursEvents();
		}
		if (typeof constraintInput === 'object') {
			return expandEvent(buildEventFromInput(constraintInput));
		}
		return clientEvents(constraintInput); 
	}
	function eventContainsRange(event, range) {
		var eventStart = event.start.clone().stripZone();
		var eventEnd = t.getEventEnd(event).stripZone();
		return range.start >= eventStart && range.end <= eventEnd;
	}
	function eventIntersectsRange(event, range) {
		var eventStart = event.start.clone().stripZone();
		var eventEnd = t.getEventEnd(event).stripZone();
		return range.start < eventEnd && range.end > eventStart;
	}
}
function backupEventDates(event) {
	event._allDay = event.allDay;
	event._start = event.start.clone();
	event._end = event.end ? event.end.clone() : null;
}
;;
var BasicView = fcViews.basic = View.extend({
	dayGrid: null, 
	dayNumbersVisible: false, 
	weekNumbersVisible: false, 
	weekNumberWidth: null, 
	headRowEl: null, 
	initialize: function() {
		this.dayGrid = new DayGrid(this);
		this.coordMap = this.dayGrid.coordMap; 
	},
	setRange: function(range) {
		View.prototype.setRange.call(this, range); 
		this.dayGrid.breakOnWeeks = /year|month|week/.test(this.intervalUnit); 
		this.dayGrid.setRange(range);
	},
	computeRange: function(date) {
		var range = View.prototype.computeRange.call(this, date); 
		if (/year|month/.test(range.intervalUnit)) {
			range.start.startOf('week');
			range.start = this.skipHiddenDays(range.start);
			if (range.end.weekday()) {
				range.end.add(1, 'week').startOf('week');
				range.end = this.skipHiddenDays(range.end, -1, true); 
			}
		}
		return range;
	},
	render: function() {
		this.dayNumbersVisible = this.dayGrid.rowCnt > 1; 
		this.weekNumbersVisible = this.opt('weekNumbers');
		this.dayGrid.numbersVisible = this.dayNumbersVisible || this.weekNumbersVisible;
		this.el.addClass('fc-basic-view').html(this.renderHtml());
		this.headRowEl = this.el.find('thead .fc-row');
		this.scrollerEl = this.el.find('.fc-day-grid-container');
		this.dayGrid.coordMap.containerEl = this.scrollerEl; 
		this.dayGrid.el = this.el.find('.fc-day-grid');
		this.dayGrid.render(this.hasRigidRows());
	},
	destroy: function() {
		this.dayGrid.destroy();
		View.prototype.destroy.call(this); 
	},
	renderHtml: function() {
		return '' +
			'<table>' +
				'<thead>' +
					'<tr>' +
						'<td class="' + this.widgetHeaderClass + '">' +
							this.dayGrid.headHtml() + 
						'</td>' +
					'</tr>' +
				'</thead>' +
				'<tbody>' +
					'<tr>' +
						'<td class="' + this.widgetContentClass + '">' +
							'<div class="fc-day-grid-container">' +
								'<div class="fc-day-grid"/>' +
							'</div>' +
						'</td>' +
					'</tr>' +
				'</tbody>' +
			'</table>';
	},
	headIntroHtml: function() {
		if (this.weekNumbersVisible) {
			return '' +
				'<th class="fc-week-number ' + this.widgetHeaderClass + '" ' + this.weekNumberStyleAttr() + '>' +
					'<span>' + 
						htmlEscape(this.opt('weekNumberTitle')) +
					'</span>' +
				'</th>';
		}
	},
	numberIntroHtml: function(row) {
		if (this.weekNumbersVisible) {
			return '' +
				'<td class="fc-week-number" ' + this.weekNumberStyleAttr() + '>' +
					'<span>' + 
						this.calendar.calculateWeekNumber(this.dayGrid.getCell(row, 0).start) +
					'</span>' +
				'</td>';
		}
	},
	dayIntroHtml: function() {
		if (this.weekNumbersVisible) {
			return '<td class="fc-week-number ' + this.widgetContentClass + '" ' +
				this.weekNumberStyleAttr() + '></td>';
		}
	},
	introHtml: function() {
		if (this.weekNumbersVisible) {
			return '<td class="fc-week-number" ' + this.weekNumberStyleAttr() + '></td>';
		}
	},
	numberCellHtml: function(cell) {
		var date = cell.start;
		var classes;
		if (!this.dayNumbersVisible) { 
			return '<td/>'; 
		}
		classes = this.dayGrid.getDayClasses(date);
		classes.unshift('fc-day-number');
		return '' +
			'<td class="' + classes.join(' ') + '" data-date="' + date.format() + '">' +
				date.date() +
			'</td>';
	},
	weekNumberStyleAttr: function() {
		if (this.weekNumberWidth !== null) {
			return 'style="width:' + this.weekNumberWidth + 'px"';
		}
		return '';
	},
	hasRigidRows: function() {
		var eventLimit = this.opt('eventLimit');
		return eventLimit && typeof eventLimit !== 'number';
	},
	updateWidth: function() {
		if (this.weekNumbersVisible) {
			this.weekNumberWidth = matchCellWidths(
				this.el.find('.fc-week-number')
			);
		}
	},
	setHeight: function(totalHeight, isAuto) {
		var eventLimit = this.opt('eventLimit');
		var scrollerHeight;
		unsetScroller(this.scrollerEl);
		uncompensateScroll(this.headRowEl);
		this.dayGrid.destroySegPopover(); 
		if (eventLimit && typeof eventLimit === 'number') {
			this.dayGrid.limitRows(eventLimit); 
		}
		scrollerHeight = this.computeScrollerHeight(totalHeight);
		this.setGridHeight(scrollerHeight, isAuto);
		if (eventLimit && typeof eventLimit !== 'number') {
			this.dayGrid.limitRows(eventLimit); 
		}
		if (!isAuto && setPotentialScroller(this.scrollerEl, scrollerHeight)) { 
			compensateScroll(this.headRowEl, getScrollbarWidths(this.scrollerEl));
			scrollerHeight = this.computeScrollerHeight(totalHeight);
			this.scrollerEl.height(scrollerHeight);
			this.restoreScroll();
		}
	},
	setGridHeight: function(height, isAuto) {
		if (isAuto) {
			undistributeHeight(this.dayGrid.rowEls); 
		}
		else {
			distributeHeight(this.dayGrid.rowEls, height, true); 
		}
	},
	renderEvents: function(events) {
		this.dayGrid.renderEvents(events);
		this.updateHeight(); 
	},
	getEventSegs: function() {
		return this.dayGrid.getEventSegs();
	},
	destroyEvents: function() {
		this.recordScroll(); 
		this.dayGrid.destroyEvents();
	},
	renderDrag: function(dropLocation, seg) {
		return this.dayGrid.renderDrag(dropLocation, seg);
	},
	destroyDrag: function() {
		this.dayGrid.destroyDrag();
	},
	renderSelection: function(range) {
		this.dayGrid.renderSelection(range);
	},
	destroySelection: function() {
		this.dayGrid.destroySelection();
	}
});
;;
setDefaults({
	fixedWeekCount: true
});
var MonthView = fcViews.month = BasicView.extend({
	computeRange: function(date) {
		var range = BasicView.prototype.computeRange.call(this, date); 
		if (this.isFixedWeeks()) {
			range.end.add(
				6 - range.end.diff(range.start, 'weeks'),
				'weeks'
			);
		}
		return range;
	},
	setGridHeight: function(height, isAuto) {
		isAuto = isAuto || this.opt('weekMode') === 'variable'; 
		if (isAuto) {
			height *= this.rowCnt / 6;
		}
		distributeHeight(this.dayGrid.rowEls, height, !isAuto); 
	},
	isFixedWeeks: function() {
		var weekMode = this.opt('weekMode'); 
		if (weekMode) {
			return weekMode === 'fixed'; 
		}
		return this.opt('fixedWeekCount');
	}
});
MonthView.duration = { months: 1 };
;;
fcViews.basicWeek = {
	type: 'basic',
	duration: { weeks: 1 }
};
;;
fcViews.basicDay = {
	type: 'basic',
	duration: { days: 1 }
};
;;
setDefaults({
	allDaySlot: true,
	allDayText: 'all-day',
	scrollTime: '06:00:00',
	slotDuration: '00:30:00',
	minTime: '00:00:00',
	maxTime: '24:00:00',
	slotEventOverlap: true
});
var AGENDA_ALL_DAY_EVENT_LIMIT = 5;
fcViews.agenda = View.extend({ 
	timeGrid: null, 
	dayGrid: null, 
	axisWidth: null, 
	noScrollRowEls: null, 
	bottomRuleEl: null,
	bottomRuleHeight: null,
	initialize: function() {
		this.timeGrid = new TimeGrid(this);
		if (this.opt('allDaySlot')) { 
			this.dayGrid = new DayGrid(this); 
			this.coordMap = new ComboCoordMap([
				this.dayGrid.coordMap,
				this.timeGrid.coordMap
			]);
		}
		else {
			this.coordMap = this.timeGrid.coordMap;
		}
	},
	setRange: function(range) {
		View.prototype.setRange.call(this, range); 
		this.timeGrid.setRange(range);
		if (this.dayGrid) {
			this.dayGrid.setRange(range);
		}
	},
	render: function() {
		this.el.addClass('fc-agenda-view').html(this.renderHtml());
		this.scrollerEl = this.el.find('.fc-time-grid-container');
		this.timeGrid.coordMap.containerEl = this.scrollerEl; 
		this.timeGrid.el = this.el.find('.fc-time-grid');
		this.timeGrid.render();
		this.bottomRuleEl = $('<hr class="' + this.widgetHeaderClass + '"/>')
			.appendTo(this.timeGrid.el); 
		if (this.dayGrid) {
			this.dayGrid.el = this.el.find('.fc-day-grid');
			this.dayGrid.render();
			this.dayGrid.bottomCoordPadding = this.dayGrid.el.next('hr').outerHeight();
		}
		this.noScrollRowEls = this.el.find('.fc-row:not(.fc-scroller *)'); 
	},
	destroy: function() {
		this.timeGrid.destroy();
		if (this.dayGrid) {
			this.dayGrid.destroy();
		}
		View.prototype.destroy.call(this); 
	},
	renderHtml: function() {
		return '' +
			'<table>' +
				'<thead>' +
					'<tr>' +
						'<td class="' + this.widgetHeaderClass + '">' +
							this.timeGrid.headHtml() + 
						'</td>' +
					'</tr>' +
				'</thead>' +
				'<tbody>' +
					'<tr>' +
						'<td class="' + this.widgetContentClass + '">' +
							(this.dayGrid ?
								'<div class="fc-day-grid"/>' +
								'<hr class="' + this.widgetHeaderClass + '"/>' :
								''
								) +
							'<div class="fc-time-grid-container">' +
								'<div class="fc-time-grid"/>' +
							'</div>' +
						'</td>' +
					'</tr>' +
				'</tbody>' +
			'</table>';
	},
	headIntroHtml: function() {
		var date;
		var weekNumber;
		var weekTitle;
		var weekText;
		if (this.opt('weekNumbers')) {
			date = this.timeGrid.getCell(0).start;
			weekNumber = this.calendar.calculateWeekNumber(date);
			weekTitle = this.opt('weekNumberTitle');
			if (this.opt('isRTL')) {
				weekText = weekNumber + weekTitle;
			}
			else {
				weekText = weekTitle + weekNumber;
			}
			return '' +
				'<th class="fc-axis fc-week-number ' + this.widgetHeaderClass + '" ' + this.axisStyleAttr() + '>' +
					'<span>' + 
						htmlEscape(weekText) +
					'</span>' +
				'</th>';
		}
		else {
			return '<th class="fc-axis ' + this.widgetHeaderClass + '" ' + this.axisStyleAttr() + '></th>';
		}
	},
	dayIntroHtml: function() {
		return '' +
			'<td class="fc-axis ' + this.widgetContentClass + '" ' + this.axisStyleAttr() + '>' +
				'<span>' + 
					(this.opt('allDayHtml') || htmlEscape(this.opt('allDayText'))) +
				'</span>' +
			'</td>';
	},
	slotBgIntroHtml: function() {
		return '<td class="fc-axis ' + this.widgetContentClass + '" ' + this.axisStyleAttr() + '></td>';
	},
	introHtml: function() {
		return '<td class="fc-axis" ' + this.axisStyleAttr() + '></td>';
	},
	axisStyleAttr: function() {
		if (this.axisWidth !== null) {
			 return 'style="width:' + this.axisWidth + 'px"';
		}
		return '';
	},
	updateSize: function(isResize) {
		if (isResize) {
			this.timeGrid.resize();
		}
		View.prototype.updateSize.call(this, isResize);
	},
	updateWidth: function() {
		this.axisWidth = matchCellWidths(this.el.find('.fc-axis'));
	},
	setHeight: function(totalHeight, isAuto) {
		var eventLimit;
		var scrollerHeight;
		if (this.bottomRuleHeight === null) {
			this.bottomRuleHeight = this.bottomRuleEl.outerHeight();
		}
		this.bottomRuleEl.hide(); 
		this.scrollerEl.css('overflow', '');
		unsetScroller(this.scrollerEl);
		uncompensateScroll(this.noScrollRowEls);
		if (this.dayGrid) {
			this.dayGrid.destroySegPopover(); 
			eventLimit = this.opt('eventLimit');
			if (eventLimit && typeof eventLimit !== 'number') {
				eventLimit = AGENDA_ALL_DAY_EVENT_LIMIT; 
			}
			if (eventLimit) {
				this.dayGrid.limitRows(eventLimit);
			}
		}
		if (!isAuto) { 
			scrollerHeight = this.computeScrollerHeight(totalHeight);
			if (setPotentialScroller(this.scrollerEl, scrollerHeight)) { 
				compensateScroll(this.noScrollRowEls, getScrollbarWidths(this.scrollerEl));
				scrollerHeight = this.computeScrollerHeight(totalHeight);
				this.scrollerEl.height(scrollerHeight);
				this.restoreScroll();
			}
			else { 
				this.scrollerEl.height(scrollerHeight).css('overflow', 'hidden'); 
				this.bottomRuleEl.show();
			}
		}
	},
	initializeScroll: function() {
		var _this = this;
		var scrollTime = moment.duration(this.opt('scrollTime'));
		var top = this.timeGrid.computeTimeTop(scrollTime);
		top = Math.ceil(top);
		if (top) {
			top++; 
		}
		function scroll() {
			_this.scrollerEl.scrollTop(top);
		}
		scroll();
		setTimeout(scroll, 0); 
	},
	renderEvents: function(events) {
		var dayEvents = [];
		var timedEvents = [];
		var daySegs = [];
		var timedSegs;
		var i;
		for (i = 0; i < events.length; i++) {
			if (events[i].allDay) {
				dayEvents.push(events[i]);
			}
			else {
				timedEvents.push(events[i]);
			}
		}
		timedSegs = this.timeGrid.renderEvents(timedEvents);
		if (this.dayGrid) {
			daySegs = this.dayGrid.renderEvents(dayEvents);
		}
		this.updateHeight();
	},
	getEventSegs: function() {
		return this.timeGrid.getEventSegs().concat(
			this.dayGrid ? this.dayGrid.getEventSegs() : []
		);
	},
	destroyEvents: function() {
		this.recordScroll();
		this.timeGrid.destroyEvents();
		if (this.dayGrid) {
			this.dayGrid.destroyEvents();
		}
	},
	renderDrag: function(dropLocation, seg) {
		if (dropLocation.start.hasTime()) {
			return this.timeGrid.renderDrag(dropLocation, seg);
		}
		else if (this.dayGrid) {
			return this.dayGrid.renderDrag(dropLocation, seg);
		}
	},
	destroyDrag: function() {
		this.timeGrid.destroyDrag();
		if (this.dayGrid) {
			this.dayGrid.destroyDrag();
		}
	},
	renderSelection: function(range) {
		if (range.start.hasTime() || range.end.hasTime()) {
			this.timeGrid.renderSelection(range);
		}
		else if (this.dayGrid) {
			this.dayGrid.renderSelection(range);
		}
	},
	destroySelection: function() {
		this.timeGrid.destroySelection();
		if (this.dayGrid) {
			this.dayGrid.destroySelection();
		}
	}
});
;;
fcViews.agendaWeek = {
	type: 'agenda',
	duration: { weeks: 1 }
};
;;
fcViews.agendaDay = {
	type: 'agenda',
	duration: { days: 1 }
};
;;
});
