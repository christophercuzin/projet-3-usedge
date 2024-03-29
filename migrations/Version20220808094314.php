<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220808094314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, answer LONGTEXT NOT NULL, number_order INT NOT NULL, INDEX IDX_DADD4A251E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE answer_request (id INT AUTO_INCREMENT NOT NULL, research_request_id INT DEFAULT NULL, answer LONGTEXT NOT NULL, name VARCHAR(255) DEFAULT NULL, question VARCHAR(255) NOT NULL, multiple_answers LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_73D740EE8664BECD (research_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE canvas_workshops (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE component (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, is_mandatory TINYINT(1) NOT NULL, title VARCHAR(255) DEFAULT NULL, question LONGTEXT DEFAULT NULL, helper_text LONGTEXT DEFAULT NULL, dtype VARCHAR(255) NOT NULL, add_ahelpertext TINYINT(1) DEFAULT NULL, low_label VARCHAR(255) DEFAULT NULL, high_label VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research_plan (id INT AUTO_INCREMENT NOT NULL, research_request_id INT NOT NULL, coach VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, creation_date DATE NOT NULL, UNIQUE INDEX UNIQ_88EA6BAD8664BECD (research_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research_plan_section (id INT AUTO_INCREMENT NOT NULL, research_plan_id INT NOT NULL, title VARCHAR(255) NOT NULL, workshop_name VARCHAR(255) NOT NULL, workshop_description LONGTEXT NOT NULL, recommendation LONGTEXT NOT NULL, objectives LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_D78BB2677137B281 (research_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research_request (id INT AUTO_INCREMENT NOT NULL, research_template_id INT DEFAULT NULL, creation_date DATETIME NOT NULL, status VARCHAR(255) NOT NULL, project VARCHAR(255) NOT NULL, owner VARCHAR(255) NOT NULL, INDEX IDX_A2393F854677FCD4 (research_template_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE research_template (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, coach VARCHAR(255) NOT NULL, icon VARCHAR(255) NOT NULL, status VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_component (id INT AUTO_INCREMENT NOT NULL, research_template_id INT DEFAULT NULL, component_id INT DEFAULT NULL, number_order INT NOT NULL, INDEX IDX_80ADD7E14677FCD4 (research_template_id), INDEX IDX_80ADD7E1E2ABAFFF (component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE answer ADD CONSTRAINT FK_DADD4A251E27F6BF FOREIGN KEY (question_id) REFERENCES component (id)');
        $this->addSql('ALTER TABLE answer_request ADD CONSTRAINT FK_73D740EE8664BECD FOREIGN KEY (research_request_id) REFERENCES research_request (id)');
        $this->addSql('ALTER TABLE research_plan ADD CONSTRAINT FK_88EA6BAD8664BECD FOREIGN KEY (research_request_id) REFERENCES research_request (id)');
        $this->addSql('ALTER TABLE research_plan_section ADD CONSTRAINT FK_D78BB2677137B281 FOREIGN KEY (research_plan_id) REFERENCES research_plan (id)');
        $this->addSql('ALTER TABLE research_request ADD CONSTRAINT FK_A2393F854677FCD4 FOREIGN KEY (research_template_id) REFERENCES research_template (id)');
        $this->addSql('ALTER TABLE template_component ADD CONSTRAINT FK_80ADD7E14677FCD4 FOREIGN KEY (research_template_id) REFERENCES research_template (id)');
        $this->addSql('ALTER TABLE template_component ADD CONSTRAINT FK_80ADD7E1E2ABAFFF FOREIGN KEY (component_id) REFERENCES component (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE answer DROP FOREIGN KEY FK_DADD4A251E27F6BF');
        $this->addSql('ALTER TABLE template_component DROP FOREIGN KEY FK_80ADD7E1E2ABAFFF');
        $this->addSql('ALTER TABLE research_plan_section DROP FOREIGN KEY FK_D78BB2677137B281');
        $this->addSql('ALTER TABLE answer_request DROP FOREIGN KEY FK_73D740EE8664BECD');
        $this->addSql('ALTER TABLE research_plan DROP FOREIGN KEY FK_88EA6BAD8664BECD');
        $this->addSql('ALTER TABLE research_request DROP FOREIGN KEY FK_A2393F854677FCD4');
        $this->addSql('ALTER TABLE template_component DROP FOREIGN KEY FK_80ADD7E14677FCD4');
        $this->addSql('DROP TABLE answer');
        $this->addSql('DROP TABLE answer_request');
        $this->addSql('DROP TABLE canvas_workshops');
        $this->addSql('DROP TABLE component');
        $this->addSql('DROP TABLE research_plan');
        $this->addSql('DROP TABLE research_plan_section');
        $this->addSql('DROP TABLE research_request');
        $this->addSql('DROP TABLE research_template');
        $this->addSql('DROP TABLE template_component');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
