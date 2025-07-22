public function rules()
{
    return [
        'video' => 'required|exists:videos,id',
    ];
}