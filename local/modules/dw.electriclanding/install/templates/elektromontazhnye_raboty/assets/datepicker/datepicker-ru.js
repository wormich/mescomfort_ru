jQuery(function($){
	$.datepicker.regional['ru'] = {
		closeText: '�������',
		prevText: '�����',
		nextText: '������',
		currentText: '�������',
		monthNames: ['������','�������','����','������','���','����','����','������','��������','�������','������','�������'],
		monthNamesShort: ['���','���','���','���','���','���','���','���','���','���','���','���'],
		dayNames: ['�����������','�����������','�������','�����','�������','�������','�������'],
		dayNamesShort: ['���','���','���','���','���','���','���'],
		dayNamesMin: ['��','��','��','��','��','��','��'],
		dateFormat: 'yy-mm-dd', firstDay: 1,
		isRTL: false};
	$.datepicker.setDefaults($.datepicker.regional['ru']);
});

function get_date(dt) {
	var s = dt.split('-');
	if (s[1].substring(0,1) == '0') {
		s[1] = s[1].substring(1,2);
	}
	if (s[2].substring(0,1) == '0') {
		s[2] = s[2].substring(1,2);
	}
	rus_month = ['������','�������','�����','������','���','����','����','�������','��������','�������','������','�������'];
	return parseInt(s[2])+' '+rus_month[parseInt(s[1])-1]+' '+s[0];
}

function get_date_object(dt) {
	var s = dt.split('-');
	return new Date(parseInt(s[0], 10), parseInt(s[1], 10)-1, parseInt(s[2], 10));
}