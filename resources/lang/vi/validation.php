<?php

return [
    'required' => ':attribute không được bỏ trống',
    'email' => ':attribute không hợp lệ',
    'min' => ':attribute phải có ít nhất :min ký tự',
    'exists' => ':attribute không tồn tại',
    'unique' => ':attribute đã tồn tại',
    'check_in' => [
        'date' => ':attribute phải là ngày hợp lệ',
        'after_or_equal' => ':attribute phải sau hoặc bằng ngày hôm nay',
    ],
    'check_out' => [
        'date' => ':attribute phải là ngày hợp lệ',
        'after' => ':attribute phải sau ngày hôm nay',
    ],
];