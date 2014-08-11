<?php

namespace Orcamentos\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20140807204731 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, cnpj VARCHAR(14) NOT NULL, corporateName VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, logotype VARCHAR(255) DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, responsable VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_C7440455979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, plan_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) DEFAULT NULL, taxes DOUBLE PRECISION NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, site VARCHAR(255) DEFAULT NULL, logotype VARCHAR(255) DEFAULT NULL, responsable VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_4FBF094FE899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, contractType VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION DEFAULT NULL, quoteLimit INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE privatenote (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, user_id INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_FFBFCCE1166D1F9C (project_id), INDEX IDX_FFBFCCE1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tags VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_2FB3D0EE19EB6921 (client_id), INDEX IDX_2FB3D0EE979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE quote (id INT AUTO_INCREMENT NOT NULL, project_id INT DEFAULT NULL, dueDate DATETIME DEFAULT NULL, taxes DOUBLE PRECISION NOT NULL, version VARCHAR(150) NOT NULL, status INT NOT NULL, profit DOUBLE PRECISION NOT NULL, commission DOUBLE PRECISION NOT NULL, privateNotes LONGTEXT DEFAULT NULL, deadline LONGTEXT DEFAULT NULL, priceDescription LONGTEXT DEFAULT NULL, paymentType LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_6B71CBF4166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, type_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, cost DOUBLE PRECISION NOT NULL, equipmentLife INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_BC91F416979B1AD6 (company_id), INDEX IDX_BC91F416C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE resourcequote (id INT AUTO_INCREMENT NOT NULL, resource_id INT DEFAULT NULL, quote_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, value DOUBLE PRECISION NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_39741B5589329D25 (resource_id), INDEX IDX_39741B55DB805178 (quote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE share (id INT AUTO_INCREMENT NOT NULL, quote_id INT DEFAULT NULL, email VARCHAR(150) NOT NULL, hash VARCHAR(255) NOT NULL, shortUrl VARCHAR(255) NOT NULL, sent TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_EF069D5ADB805178 (quote_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE sharenote (id INT AUTO_INCREMENT NOT NULL, share_id INT DEFAULT NULL, note LONGTEXT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_F829661E2AE63FDB (share_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(100) NOT NULL, admin TINYINT(1) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("CREATE TABLE view (id INT AUTO_INCREMENT NOT NULL, share_id INT DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME DEFAULT NULL, INDEX IDX_FEFDAB8E2AE63FDB (share_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE client ADD CONSTRAINT FK_C7440455979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE company ADD CONSTRAINT FK_4FBF094FE899029B FOREIGN KEY (plan_id) REFERENCES plan (id)");
        $this->addSql("ALTER TABLE privatenote ADD CONSTRAINT FK_FFBFCCE1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)");
        $this->addSql("ALTER TABLE privatenote ADD CONSTRAINT FK_FFBFCCE1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)");
        $this->addSql("ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)");
        $this->addSql("ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)");
        $this->addSql("ALTER TABLE resource ADD CONSTRAINT FK_BC91F416979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE resource ADD CONSTRAINT FK_BC91F416C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)");
        $this->addSql("ALTER TABLE resourcequote ADD CONSTRAINT FK_39741B5589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)");
        $this->addSql("ALTER TABLE resourcequote ADD CONSTRAINT FK_39741B55DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)");
        $this->addSql("ALTER TABLE share ADD CONSTRAINT FK_EF069D5ADB805178 FOREIGN KEY (quote_id) REFERENCES quote (id)");
        $this->addSql("ALTER TABLE sharenote ADD CONSTRAINT FK_F829661E2AE63FDB FOREIGN KEY (share_id) REFERENCES share (id)");
        $this->addSql("ALTER TABLE user ADD CONSTRAINT FK_8D93D649979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)");
        $this->addSql("ALTER TABLE view ADD CONSTRAINT FK_FEFDAB8E2AE63FDB FOREIGN KEY (share_id) REFERENCES share (id)");
    }

    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE19EB6921");
        $this->addSql("ALTER TABLE client DROP FOREIGN KEY FK_C7440455979B1AD6");
        $this->addSql("ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE979B1AD6");
        $this->addSql("ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416979B1AD6");
        $this->addSql("ALTER TABLE user DROP FOREIGN KEY FK_8D93D649979B1AD6");
        $this->addSql("ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416C54C8C93");
        $this->addSql("ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FE899029B");
        $this->addSql("ALTER TABLE privatenote DROP FOREIGN KEY FK_FFBFCCE1166D1F9C");
        $this->addSql("ALTER TABLE quote DROP FOREIGN KEY FK_6B71CBF4166D1F9C");
        $this->addSql("ALTER TABLE resourcequote DROP FOREIGN KEY FK_39741B55DB805178");
        $this->addSql("ALTER TABLE share DROP FOREIGN KEY FK_EF069D5ADB805178");
        $this->addSql("ALTER TABLE resourcequote DROP FOREIGN KEY FK_39741B5589329D25");
        $this->addSql("ALTER TABLE sharenote DROP FOREIGN KEY FK_F829661E2AE63FDB");
        $this->addSql("ALTER TABLE view DROP FOREIGN KEY FK_FEFDAB8E2AE63FDB");
        $this->addSql("ALTER TABLE privatenote DROP FOREIGN KEY FK_FFBFCCE1A76ED395");
        $this->addSql("DROP TABLE client");
        $this->addSql("DROP TABLE company");
        $this->addSql("DROP TABLE type");
        $this->addSql("DROP TABLE plan");
        $this->addSql("DROP TABLE privatenote");
        $this->addSql("DROP TABLE project");
        $this->addSql("DROP TABLE quote");
        $this->addSql("DROP TABLE resource");
        $this->addSql("DROP TABLE resourcequote");
        $this->addSql("DROP TABLE share");
        $this->addSql("DROP TABLE sharenote");
        $this->addSql("DROP TABLE user");
        $this->addSql("DROP TABLE view");
    }
}
