<?php

return [
  [
    'module' => 'biz.jmaconsulting.parentchildregistration',
    'name' => 'Additional Participant Registration Status',
    'update' => 'always',
    'entity' => 'ParticipantStatusType',
    'params' => [
      'version' => 3,
      'name' => 'additional_participant',
      'label' => 'Additional Participant',
      'class' => 'Positive',
      'is_active' => 1,
      'is_reserved' => 1,
      'is_counted' => 0,
      'visibility_id' => 2,
      'weight' => 17,
    ],
  ],
];
