<?php

/**
 * ---------------------------------------------------------------------
 *
 * GLPI - Gestionnaire Libre de Parc Informatique
 *
 * http://glpi-project.org
 *
 * @copyright 2015-2024 Teclib' and contributors.
 * @copyright 2003-2014 by the INDEPNET Development Team.
 * @licence   https://www.gnu.org/licenses/gpl-3.0.html
 *
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * ---------------------------------------------------------------------
 */

namespace Glpi\Form\Destination\CommonITILField;

use Glpi\DBAL\JsonFieldInterface;
use Glpi\Form\Export\Context\ConfigWithForeignKeysInterface;
use Glpi\Form\Export\Context\ForeignKey\ForeignKeyHandler;
use Glpi\Form\Export\Context\ForeignKey\QuestionForeignKeyHandler;
use Glpi\Form\Export\Specification\ContentSpecificationInterface;
use ITILCategory;
use Override;

final class ITILCategoryFieldConfig implements JsonFieldInterface, ConfigWithForeignKeysInterface
{
    // Unique reference to hardcoded names used for serialization and forms input names
    public const STRATEGY = 'strategy';
    public const SPECIFIC_QUESTION_ID = 'specific_question_id';
    public const SPECIFIC_ITILCATEGORY_ID = 'specific_itilcategory_id';

    public function __construct(
        private ITILCategoryFieldStrategy $strategy,
        private ?int $specific_question_id = null,
        private ?int $specific_itilcategory_id = null,
    ) {
    }

    #[Override]
    public static function listForeignKeysHandlers(ContentSpecificationInterface $content_spec): array
    {
        return [
            new ForeignKeyHandler(self::SPECIFIC_ITILCATEGORY_ID, ITILCategory::class),
            new QuestionForeignKeyHandler(self::SPECIFIC_QUESTION_ID)
        ];
    }

    #[Override]
    public static function jsonDeserialize(array $data): self
    {
        $strategy = ITILCategoryFieldStrategy::tryFrom($data[self::STRATEGY] ?? "");
        if ($strategy === null) {
            $strategy = ITILCategoryFieldStrategy::LAST_VALID_ANSWER;
        }

        return new self(
            strategy: $strategy,
            specific_question_id: $data[self::SPECIFIC_QUESTION_ID] ?? null,
            specific_itilcategory_id: $data[self::SPECIFIC_ITILCATEGORY_ID] ?? null
        );
    }

    #[Override]
    public function jsonSerialize(): array
    {
        return [
            self::STRATEGY => $this->strategy->value,
            self::SPECIFIC_QUESTION_ID => $this->specific_question_id,
            self::SPECIFIC_ITILCATEGORY_ID => $this->specific_itilcategory_id,
        ];
    }

    public function getStrategy(): ITILCategoryFieldStrategy
    {
        return $this->strategy;
    }

    public function getSpecificQuestionId(): ?int
    {
        return $this->specific_question_id;
    }

    public function getSpecificITILCategoryID(): ?int
    {
        return $this->specific_itilcategory_id;
    }
}