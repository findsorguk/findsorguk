<?php 
class Pas_View_Helper_Grapher extends Zend_View_Helper_Abstract

{

public function Grapher($type = 'pie',$title,$data)
	{
	return $this->buildGraph($type,$title,$data);
	}

public function buildGraph($type,$title,$data)
	{
	$chart = new Pas_GoogChart();
	$color = array(
			'#99C754',
			'#54C7C5',
			'#999999',
		);

$chart->setChartAttrs( array(
    'type' => 'pie',
    'data' => $data,
    'size' => array( 450, 200 ),
	'color' => $color,
	'title' => $title,
    ));
	
return $chart;
	
	}
}