<?php
declare(strict_types=1);

namespace App\Domain\Interface;

interface ArticlesInterface
{
		/**
		 * Get articles
		 *
		 * @return array<mixed> Articles
		 */
		public function getArticles(): array;
}