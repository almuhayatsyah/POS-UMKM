<?php

return [
    'accepted'             => ':Attribute harus diterima.',
    'active_url'           => ':Attribute bukan URL yang valid.',
    'after'                => ':Attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':Attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':Attribute hanya boleh berisi hurup.',
    'alpha_dash'           => ':Attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':Attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':Attribute harus berupa array.',
    'before'               => ':Attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':Attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'numeric' => ':Attribute harus antara :min dan :max.',
        'file'    => ':Attribute harus antara :min dan :max kilobytes.',
        'string'  => ':Attribute harus antara :min dan :max karakter.',
        'array'   => ':Attribute harus antara :min dan :max item.',
    ],
    'boolean'              => ':Attribute harus berupa true atau false.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'date'                 => ':Attribute bukan tanggal yang valid.',
    'date_format'          => ':Attribute tidak cocok dengan format :format.',
    'different'            => ':Attribute dan :other harus berbeda.',
    'digits'               => ':Attribute harus berupa angka :digits.',
    'digits_between'       => ':Attribute harus antara angka :min dan :max.',
    'dimensions'           => ':Attribute tidak memiliki dimensi gambar yang valid.',
    'distinct'             => ':Attribute memiliki nilai yang duplikat.',
    'email'                => ':Attribute harus berupa alamat surel yang valid.',
    'exists'               => ':Attribute yang dipilih tidak valid.',
    'file'                 => ':Attribute harus berupa sebuah berkas.',
    'filled'               => ':Attribute harus memiliki nilai.',
    'gt'                   => [
        'numeric' => ':Attribute harus lebih besar dari :value.',
        'file'    => ':Attribute harus lebih besar dari :value kilobytes.',
        'string'  => ':Attribute harus lebih besar dari :value karakter.',
        'array'   => ':Attribute harus lebih dari :value item.',
    ],
    'gte'                  => [
        'numeric' => ':Attribute harus lebih besar dari atau sama dengan :value.',
        'file'    => ':Attribute harus lebih besar dari atau sama dengan :value kilobytes.',
        'string'  => ':Attribute harus lebih besar dari atau sama dengan :value karakter.',
        'array'   => ':Attribute harus mempunyai :value item atau lebih.',
    ],
    'image'                => ':Attribute harus berupa gambar.',
    'in'                   => ':Attribute yang dipilih tidak valid.',
    'in_array'             => ':Attribute tidak ada di dalam :other.',
    'integer'              => ':Attribute harus merupakan bilangan bulat.',
    'ip'                   => ':Attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':Attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':Attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':Attribute harus berupa JSON string yang valid.',
    'lt'                   => [
        'numeric' => ':Attribute harus kurang dari :value.',
        'file'    => ':Attribute harus kurang dari :value kilobytes.',
        'string'  => ':Attribute harus kurang dari :value karakter.',
        'array'   => ':Attribute harus kurang dari :value item.',
    ],
    'lte'                  => [
        'numeric' => ':Attribute harus kurang dari atau sama dengan :value.',
        'file'    => ':Attribute harus kurang dari atau sama dengan :value kilobytes.',
        'string'  => ':Attribute harus kurang dari atau sama dengan :value karakter.',
        'array'   => ':Attribute harus tidak lebih dari :value item.',
    ],
    'max'                  => [
        'numeric' => ':Attribute seharusnya tidak lebih dari :max.',
        'file'    => ':Attribute seharusnya tidak lebih dari :max kilobytes.',
        'string'  => ':Attribute seharusnya tidak lebih dari :max karakter.',
        'array'   => ':Attribute seharusnya tidak lebih dari :max item.',
    ],
    'mimes'                => ':Attribute harus dokumen berjenis : :values.',
    'mimetypes'            => ':Attribute harus dokumen berjenis : :values.',
    'min'                  => [
        'numeric' => ':Attribute harus minimal :min.',
        'file'    => ':Attribute harus minimal :min kilobytes.',
        'string'  => ':Attribute harus minimal :min karakter.',
        'array'   => ':Attribute harus minimal :min item.',
    ],
    'not_in'               => ':Attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':Attribute harus berupa angka.',
    'present'              => ':Attribute wajib ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':Attribute wajib diisi.',
    'required_if'          => ':Attribute wajib diisi bila :other adalah :value.',
    'required_unless'      => ':Attribute wajib diisi kecuali :other memiliki nilai :values.',
    'required_with'        => ':Attribute wajib diisi bila terdapat :values.',
    'required_with_all'    => ':Attribute wajib diisi bila terdapat :values.',
    'required_without'     => ':Attribute wajib diisi bila tidak terdapat :values.',
    'required_without_all' => ':Attribute wajib diisi bila sama sekali tidak terdapat :values.',
    'same'                 => ':Attribute dan :other harus sama.',
    'size'                 => [
        'numeric' => ':Attribute harus berukuran :size.',
        'file'    => ':Attribute harus berukuran :size kilobytes.',
        'string'  => ':Attribute harus berukuran :size karakter.',
        'array'   => ':Attribute harus mengandung :size item.',
    ],
    'string'               => ':Attribute harus berupa string.',
    'timezone'             => ':Attribute harus berupa zona waktu yang valid.',
    'unique'               => ':Attribute sudah ada sebelumnya.',
    'uploaded'             => ':Attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',
    'uuid'                 => ':Attribute harus merupakan UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'nama',
        'username' => 'nama pengguna',
        'email' => 'alamat email',
        'first_name' => 'nama depan',
        'last_name' => 'nama belakang',
        'password' => 'kata sandi',
        'password_confirmation' => 'konfirmasi kata sandi',
        'city' => 'kota',
        'country' => 'negara',
        'address' => 'alamat',
        'phone' => 'telepon',
        'mobile' => 'seluler',
        'age' => 'usia',
        'sex' => 'jenis kelamin',
        'gender' => 'jenis kelamin',
        'day' => 'hari',
        'month' => 'bulan',
        'year' => 'tahun',
        'hour' => 'jam',
        'minute' => 'menit',
        'second' => 'detik',
        'title' => 'judul',
        'content' => 'konten',
        'description' => 'deskripsi',
        'excerpt' => 'kutipan',
        'date' => 'tanggal',
        'time' => 'waktu',
        'available' => 'tersedia',
        'size' => 'ukuran',
    ],
];
