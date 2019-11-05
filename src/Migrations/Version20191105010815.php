<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191105010815 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE lottery (id INT AUTO_INCREMENT NOT NULL, lottery_number VARCHAR(255) NOT NULL, size INT NOT NULL, ticket_amount DOUBLE PRECISION NOT NULL, jackpot DOUBLE PRECISION NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, start_at DATETIME NOT NULL, close_at DATETIME NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE temp_ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lottery_id INT NOT NULL, ticket_number INT NOT NULL, created_at DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_E0313D94A76ED395 (user_id), INDEX IDX_E0313D94CFAA77DD (lottery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, lottery_id INT NOT NULL, ticket_number INT NOT NULL, bitcoin_transaction_number VARCHAR(255) NOT NULL, purchased_at DATETIME DEFAULT CURRENT_TIMESTAMP, bitcoin_transaction_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_97A0ADA3A76ED395 (user_id), INDEX IDX_97A0ADA3CFAA77DD (lottery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, discr VARCHAR(255) NOT NULL, lucky_charm VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE temp_ticket ADD CONSTRAINT FK_E0313D94A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE temp_ticket ADD CONSTRAINT FK_E0313D94CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3CFAA77DD FOREIGN KEY (lottery_id) REFERENCES lottery (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE temp_ticket DROP FOREIGN KEY FK_E0313D94CFAA77DD');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3CFAA77DD');
        $this->addSql('ALTER TABLE temp_ticket DROP FOREIGN KEY FK_E0313D94A76ED395');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3A76ED395');
        $this->addSql('DROP TABLE lottery');
        $this->addSql('DROP TABLE temp_ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
    }
}
