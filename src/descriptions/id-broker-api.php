<?php return [
    'operations' => [
        'getSiteStatusInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/site/status',
            'responseModel' => 'Result',
        ],
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
        'authenticateNewUserInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/authentication',
            'responseModel' => 'Result',
            'parameters' => [
                'invite' => [
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
                'manager_email' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'require_mfa' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
                'spouse_email' => [
                    'required' => false,
                    'type' => 'string',
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
                ],
                'username' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'query',
                ],
                'email' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'query',
                ],
            ]
        ],
        'mfaCreateInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/mfa',
            'responseModel' => 'Result',
            'parameters' => [
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
                'type' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                    'enum' => ['backupcode', 'totp', 'u2f'],
                ],
                'label' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'mfaDeleteInternal' => [
            'httpMethod' => 'DELETE',
            'uri' => '/mfa/{id}',
            'responseModel' => 'Result',
            'parameters' => [
                'id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
            ],
        ],
        'mfaListInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/user/{employee_id}/mfa',
            'responseModel' => 'Result',
            'parameters' => [
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
            ],
        ],
        'mfaUpdateInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/mfa/{id}',
            'responseModel' => 'Result',
            'parameters' => [
                'id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
                'label' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
            ],
        ],
        'mfaVerifyInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/mfa/{id}/verify',
            'responseModel' => 'Result',
            'parameters' => [
                'id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
                'value' => [
                    'required' => true,
                    'type' => ['string', 'object'],
                    'location' => 'json',
                ],
            ],
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
                'manager_email' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'require_mfa' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
                'spouse_email' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'hide' => [
                    'required' => false,
                    'type' => 'string',
                    'enum' => ['yes', 'no'],
                    'location' => 'json',
                ],
            ],
        ],
        'createMethodInternal' => [
            'httpMethod' => 'POST',
            'uri' => '/method',
            'responseModel' => 'Result',
            'parameters' => [
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
                'value' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
                'created' => [
                    'required' => false,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'deleteMethodInternal' => [
            'httpMethod' => 'DELETE',
            'uri' => '/method/{uid}',
            'responseModel' => 'Result',
            'parameters' => [
                'uid' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
            ],
        ],
        'listMethodInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/user/{employee_id}/method',
            'responseModel' => 'Result',
            'parameters' => [
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
            ],
        ],
        'getMethodInternal' => [
            'httpMethod' => 'GET',
            'uri' => '/method/{uid}',
            'responseModel' => 'Result',
            'parameters' => [
                'uid' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
            ],
        ],
        'verifyMethodInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/method/{uid}/verify',
            'responseModel' => 'Result',
            'parameters' => [
                'uid' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
                ],
                'code' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json',
                ],
            ],
        ],
        'resendMethodInternal' => [
            'httpMethod' => 'PUT',
            'uri' => '/method/{uid}/resend',
            'responseModel' => 'Result',
            'parameters' => [
                'uid' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'uri'
                ],
                'employee_id' => [
                    'required' => true,
                    'type' => 'string',
                    'location' => 'json'
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
