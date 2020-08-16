<?php

namespace Vendor\Utils\Methods\Staff;

use NotaTools\Validators\BitrixDateTimeValidator;
use Rakit\Validation\RuleNotFoundException;
use Rakit\Validation\RuleQuashException;
use Rakit\Validation\Validator;
use Vendor\Utils\Exception\ValidateException;
use Vendor\Utils\Orm\Collection\StaffCollection;
use Vendor\Utils\Orm\Entity\Staff as StaffEntity;

/**
 * Class Staff
 * @package Vendor\Utils\Methods\Staff
 */
class Staff
{
    protected const PAGINATION_NAME = 'staff';
    protected const COLLECTION = StaffCollection::class;
    protected const ENTITY = StaffEntity::class;
    protected const CACHE_PREFIX = 'staff';
    protected const DEFAULT_LIMIT = 12;
    protected const DEFAULT_PAGE = 1;
    protected const DEFAULT_SORT = ['ID' => 'desc'];

    /**
     * @param Validator $validator
     * @param array     $data
     * @param array     $rules
     * @param array     $messages
     *
     * @throws ValidateException
     */
    public static function validate(Validator $validator, array $data, array $rules, array $messages = [], string $implodeGlue = '; '): void
    {
        if (!empty($messages)) {
            $validator->setMessages($messages);
        }
        try {
            $validator->addValidator('bitrix_date_time', new BitrixDateTimeValidator());
            $validation = $validator->validate($data, $rules);
        } catch (RuleQuashException|RuleNotFoundException $e) {
            throw new ValidateException($e->getMessage() . ' - ' . $e->getCode());
        }
        if ($validation->fails()) {
            // handling errors
            $errors = $validation->errors()->all();
            throw new ValidateException(implode($implodeGlue, $errors));
        }
    }

}
