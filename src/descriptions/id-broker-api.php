<?php return [
    'operations' => [
        'authenticateInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/authentication',
            'responseModel' => 'Result',
            'parameters' => [
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
        'createUserInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/user',
            'responseModel' => 'Result',
            'parameters' => [
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
                'active' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
                'locked' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
            ],
        ],
        'deactivateUserInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
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
        'getUserInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri',
                ],
            ],
        ],
        'listUsersInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/user',
            'responseModel' => 'Result',
            'parameters' => [
                'fields' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'query',
                ]
            ]
        ],
        'setPasswordInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}/password',
            'responseModel' => 'Result',
            'parameters' => [
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
        'updateUserInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/user/{employee_id}',
            'responseModel' => 'Result',
            'parameters' => [
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
                'active' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
                'locked' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
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
            ],
        ],
    ]
];
