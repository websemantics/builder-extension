<?php
[
            'name' => '{{entity_name|replace({'_':' '})|str_plural|title}}',
            'option' => [
              'read'   => 'Can read {{entity_name|replace({'_':' '})|title}} entries.',
              'write'   => 'Can write {{entity_name|replace({'_':' '})|title}} entries.',
              'delete'   => 'Can delete {{entity_name|replace({'_':' '})|title}} entries.',
            ],
    ];
