<?php return [
    'operations' => [
        'activateUser' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
                'active' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'default' => 'yes',
                    'static' => true,
                ],
            ],
        ],
        'authenticate' => [
            'httpMethod' => 'POST',
            'uri' => '/authentication',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add API authentication credential parameters. */
                
                'username' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'password' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'createUser' => [
            'httpMethod' => 'POST',
            'uri' => '/user',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'first_name' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'last_name' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'display_name' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'username' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'email' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'deactivateUser' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
                'active' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'default' => 'no',
                    'static' => true,
                ],
            ],
        ],
        'findUsers' => [
            'httpMethod' => 'GET',
            'uri' => '/user',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'username' => [
                    'required' => true,
                    'type'     => 'string',
                    'location' => 'query',
                ],
            ],
        ],
        'getUser' => [
            'httpMethod' => 'GET',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
            ],
        ],
        'listUsers' => [
            'httpMethod' => 'GET',
            'uri' => '/user',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
            ],
        ],
        'setPassword' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}/password',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
                'password' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'updateUser' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
                
                /** @todo Add authentication credential parameters. */
                
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
                'first_name' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'last_name' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'display_name' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'username' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'email' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
    ],
    'models' => [
        'Result' => [
            'type' => 'object',
            'properties' => [
                'statusCode' => ['location' => 'statusCode'],
            ],
            'additionalProperties' => [
                'location' => 'json'
            ]
        ]
    ]
];
