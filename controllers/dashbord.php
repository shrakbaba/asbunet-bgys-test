
                <div class="box-body">
                  <?php 
                  echo "<pre>";print_r($data['riskhatirasi']);
                  echo Highcharts::widget([
                    'options' => [
                        "chart" => [
                          //"type" => "bubble",
                          "type" => "packedbubble",
                          "plotBorderWidth"=>1,
                          //"zoomType"=> "xy",
                        ],
                        "title" => [
                          "text" => "Risk Haritası "
                        ],
                        /*"subtitle" => [
                          "text" => "Risk Özet Durumuna Göre"
                        ],
                        "legend" => [
                          "enabled" => false
                        ],                      
                        'plotOptions'=> [
                            'series'=> [
                                'dataLabels'=> [
                                    'enabled'=> true,
                                    'format'=> '{point.z}'
                                ]
                            ]
                        ],
                        'xAxis'=> [
                            'gridLineWidth'=> 1,
                            'title'=> [
                                'text'=> 'Olasılık*GBE'
                            ],
                            'labels'=> [
                                'format'=> '{value} [25]'
                            ],
                            'plotLines'=> [[
                                'color'=> 'black',
                                'dashStyle'=> 'dot',
                                'value'=> 0,
                                'width'=> 2,
                                'label'=> [
                                    'rotation'=> 0,
                                    'style'=> [
                                        'fontStyle'=> 'italic'
                                    ],
                                ],
                                'zIndex'=> 3
                            ]],
                        ],
                        'yAxis'=> [
                                'startOnTick'=> false,
                                'endOnTick'=> false,
                                'title'=> [
                                    'text'=> 'Varlık Değeri'
                                ],
                                'labels'=> [
                                    'format'=> '{value} [4]'
                                ],
                                'maxPadding'=> 0.2,
                                'plotLines'=> [[
                                    'color'=> 'black',
                                    'dashStyle'=> 'dot',                                  
                                    'value'=> 0,
                                    'width'=> 2,
                                    'label'=> [
                                        'align'=> 'right',
                                        'style'=> [
                                            'fontStyle'=> 'italic'
                                        ],
                                        'x'=> -10
                                    ],
                                    'zIndex'=> 3
                                ]],
                        ],
                        'tooltip'=> [
                            'useHTML'=> true,
                            'headerFormat'=> '<table>',
                            'pointFormat'=> '<tr><th colspan="2"><h3>{point.z}</h3></th></tr>'.
                                '<tr><th>Olasılık:</th><td>{point.x}</td></tr>' .
                                '<tr><th>GBE* Olasılık:</th><td>{point.y}</td></tr>',
                            'footerFormat'=> '</table>',
                            'followPointer'=> true
                        ],*/
                        'tooltip'=> [
                            'useHTML'=> true,
                            'pointFormat'=> '<b>{point.name}:</b> {point.value}m CO<sub>2</sub>'
                        ],
                        'plotOptions'=> [
                          'packedbubble'=> [
                              'minSize'=> '30%',
                              'maxSize'=> '120%',
                              'zMin'=> 0,
                              'zMax'=> 1000,
                              'layoutAlgorithm'=> [
                                  'splitSeries'=> false,
                                  'gravitationalConstant'=> 0.02
                              ],
                              'dataLabels'=> [
                                  'enabled'=> true,
                                  'format'=> '{point.name}',
                                  'filter'=> [
                                      'property'=> 'y',
                                      'operator'=> '>',
                                      'value'=> 250
                                  ],
                                  'style'=> [
                                      'color'=> 'black',
                                      'textOutline'=> 'none',
                                      'fontWeight'=> 'normal'
                                  ]
                              ]
                          ]
                        ],
                        "series" => [
                            [ 
                              "name" => "Bilgisyar" , 
                              "data" => [
                                  [ 'name' => 'Bilgisayarlara', "value" => 16 ],
                                  [ 'name' => 'erererer', "value" => 28 ],
                                  [ 'name' => 'erererer', "value" => 28 ],
                              ], 
                            ],
                            [ 
                              "name" => "Ağ" , 
                              "data" => [
                                  [ 'name' => 'xxx', "value" => 36 ],
                                  [ 'name' => 'yyy', "value" => 52 ]
                              ], 
                            ],                        
                            //$data['riskhatirasi'],
                            /* 'data'=> [
                                [ 'x'=> 95  , 'y'=> 95   , 'z'=> 13.8, 'name'=> 'BE', 'country'=> 'Belgium'       ],
                                [ 'x'=> 86.5, 'y'=> 102.9, 'z'=> 14.7, 'name'=> 'DE', 'country'=> 'Germany'       ],
                                [ 'x'=> 80.8, 'y'=> 91.5 , 'z'=> 15.8, 'name'=> 'FI', 'country'=> 'Finland'       ],
                                [ 'x'=> 80.4, 'y'=> 102.5, 'z'=> 12  , 'name'=> 'NL', 'country'=> 'Netherlands'   ],
                                [ 'x'=> 80.3, 'y'=> 86.1 , 'z'=> 11.8, 'name'=> 'SE', 'country'=> 'Sweden'        ],
                                [ 'x'=> 78.4, 'y'=> 70.1 , 'z'=> 16.6, 'name'=> 'ES', 'country'=> 'Spain'         ],
                                [ 'x'=> 74.2, 'y'=> 68.5 , 'z'=> 14.5, 'name'=> 'FR', 'country'=> 'France'        ],
                                [ 'x'=> 73.5, 'y'=> 83.1 , 'z'=> 10  , 'name'=> 'NO', 'country'=> 'Norway'        ],
                                [ 'x'=> 71  , 'y'=> 93.2 , 'z'=> 24.7, 'name'=> 'UK', 'country'=> 'United Kingdom'],
                                [ 'x'=> 69.2, 'y'=> 57.6 , 'z'=> 10.4, 'name'=> 'IT', 'country'=> 'Italy'         ],
                                [ 'x'=> 68.6, 'y'=> 20   , 'z'=> 16  , 'name'=> 'RU', 'country'=> 'Russia'        ],
                                [ 'x'=> 65.5, 'y'=> 126.4, 'z'=> 35.3, 'name'=> 'US', 'country'=> 'United States' ],
                                [ 'x'=> 65.4, 'y'=> 50.8 , 'z'=> 28.5, 'name'=> 'HU', 'country'=> 'Hungary'       ],
                                [ 'x'=> 63.4, 'y'=> 51.8 , 'z'=> 15.4, 'name'=> 'PT', 'country'=> 'Portugal'      ],
                                [ 'x'=> 64  , 'y'=> 82.9 , 'z'=> 31.3, 'name'=> 'NZ', 'country'=> 'New Zealand'   ]
                              ],*/
                         
                        ],
                      ]
                    ]);
                  ?>
                </div>