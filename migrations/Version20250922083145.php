<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250922083145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activite (id INT AUTO_INCREMENT NOT NULL, objectif_id INT NOT NULL, nom_activite VARCHAR(255) NOT NULL, descritpion VARCHAR(255) NOT NULL, INDEX IDX_B8755515157D1AD4 (objectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE budget (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, activite_id INT NOT NULL, montant_prevu DOUBLE PRECISION NOT NULL, montant_depense DOUBLE PRECISION NOT NULL, INDEX IDX_73F2F77BC18272 (projet_id), INDEX IDX_73F2F77B9B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, nom_direction VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, responsable_direction VARCHAR(255) NOT NULL, fk_mission VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_C1765B63BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, etat_civil_id INT NOT NULL, poste_id INT NOT NULL, INDEX IDX_F804D3B9CCF9E01E (departement_id), UNIQUE INDEX UNIQ_F804D3B9191476EE (etat_civil_id), INDEX IDX_F804D3B9A0905086 (poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe_projet (employe_id INT NOT NULL, projet_id INT NOT NULL, INDEX IDX_3E3387501B65292 (employe_id), INDEX IDX_3E338750C18272 (projet_id), PRIMARY KEY(employe_id, projet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, matricule VARCHAR(255) NOT NULL, date_recrutement DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_civil (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, lieu_naissance VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE indicateur_activite (id INT AUTO_INCREMENT NOT NULL, activite_id INT NOT NULL, nom_indicateur VARCHAR(255) NOT NULL, unite VARCHAR(255) NOT NULL, valeur_actuelle DOUBLE PRECISION NOT NULL, INDEX IDX_F0AE04CF9B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, nom_mission VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objectif (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, nom_objectif VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_E2F86851BE6CAE90 (mission_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objectifspecifique (id INT AUTO_INCREMENT NOT NULL, objectif_id INT NOT NULL, nom_objectif_specifique VARCHAR(255) NOT NULL, descritpion VARCHAR(255) NOT NULL, taux DOUBLE PRECISION NOT NULL, INDEX IDX_60DA6561157D1AD4 (objectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poste (id INT AUTO_INCREMENT NOT NULL, nom_poste VARCHAR(255) NOT NULL, description_poste VARCHAR(255) NOT NULL, niveau_hierarchique VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE prevision (id INT AUTO_INCREMENT NOT NULL, indicateur_activite_id INT NOT NULL, t1 DOUBLE PRECISION NOT NULL, t2 DOUBLE PRECISION NOT NULL, t3 DOUBLE PRECISION NOT NULL, t4 DOUBLE PRECISION NOT NULL, total DOUBLE PRECISION NOT NULL, lfr_lfi VARCHAR(255) NOT NULL, date_physique DATE NOT NULL, INDEX IDX_1EEB1DDEA9B05F56 (indicateur_activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, activite_id INT NOT NULL, nom_projet VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_50159CA99B0F88B1 (activite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_attendu (id INT AUTO_INCREMENT NOT NULL, prevision_id INT NOT NULL, description VARCHAR(255) NOT NULL, indicateur VARCHAR(255) NOT NULL, valeur_cible DOUBLE PRECISION NOT NULL, manque_a_gagner DOUBLE PRECISION NOT NULL, date_evaluation DATE NOT NULL, INDEX IDX_56B1E7C929E28AF8 (prevision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat_obtenu (id INT AUTO_INCREMENT NOT NULL, prevision_id INT NOT NULL, descritpion VARCHAR(255) NOT NULL, indicateur VARCHAR(255) NOT NULL, valeur_actuelle DOUBLE PRECISION NOT NULL, INDEX IDX_B253B9C629E28AF8 (prevision_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D6491B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activite ADD CONSTRAINT FK_B8755515157D1AD4 FOREIGN KEY (objectif_id) REFERENCES objectif (id)');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77BC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE budget ADD CONSTRAINT FK_73F2F77B9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE departement ADD CONSTRAINT FK_C1765B63BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9CCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9191476EE FOREIGN KEY (etat_civil_id) REFERENCES etat_civil (id)');
        $this->addSql('ALTER TABLE employe ADD CONSTRAINT FK_F804D3B9A0905086 FOREIGN KEY (poste_id) REFERENCES poste (id)');
        $this->addSql('ALTER TABLE employe_projet ADD CONSTRAINT FK_3E3387501B65292 FOREIGN KEY (employe_id) REFERENCES employe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE employe_projet ADD CONSTRAINT FK_3E338750C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE indicateur_activite ADD CONSTRAINT FK_F0AE04CF9B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE objectif ADD CONSTRAINT FK_E2F86851BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE objectifspecifique ADD CONSTRAINT FK_60DA6561157D1AD4 FOREIGN KEY (objectif_id) REFERENCES objectif (id)');
        $this->addSql('ALTER TABLE prevision ADD CONSTRAINT FK_1EEB1DDEA9B05F56 FOREIGN KEY (indicateur_activite_id) REFERENCES indicateur_activite (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA99B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id)');
        $this->addSql('ALTER TABLE resultat_attendu ADD CONSTRAINT FK_56B1E7C929E28AF8 FOREIGN KEY (prevision_id) REFERENCES prevision (id)');
        $this->addSql('ALTER TABLE resultat_obtenu ADD CONSTRAINT FK_B253B9C629E28AF8 FOREIGN KEY (prevision_id) REFERENCES prevision (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6491B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activite DROP FOREIGN KEY FK_B8755515157D1AD4');
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77BC18272');
        $this->addSql('ALTER TABLE budget DROP FOREIGN KEY FK_73F2F77B9B0F88B1');
        $this->addSql('ALTER TABLE departement DROP FOREIGN KEY FK_C1765B63BE6CAE90');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9CCF9E01E');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9191476EE');
        $this->addSql('ALTER TABLE employe DROP FOREIGN KEY FK_F804D3B9A0905086');
        $this->addSql('ALTER TABLE employe_projet DROP FOREIGN KEY FK_3E3387501B65292');
        $this->addSql('ALTER TABLE employe_projet DROP FOREIGN KEY FK_3E338750C18272');
        $this->addSql('ALTER TABLE indicateur_activite DROP FOREIGN KEY FK_F0AE04CF9B0F88B1');
        $this->addSql('ALTER TABLE objectif DROP FOREIGN KEY FK_E2F86851BE6CAE90');
        $this->addSql('ALTER TABLE objectifspecifique DROP FOREIGN KEY FK_60DA6561157D1AD4');
        $this->addSql('ALTER TABLE prevision DROP FOREIGN KEY FK_1EEB1DDEA9B05F56');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA99B0F88B1');
        $this->addSql('ALTER TABLE resultat_attendu DROP FOREIGN KEY FK_56B1E7C929E28AF8');
        $this->addSql('ALTER TABLE resultat_obtenu DROP FOREIGN KEY FK_B253B9C629E28AF8');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6491B65292');
        $this->addSql('DROP TABLE activite');
        $this->addSql('DROP TABLE budget');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE employe_projet');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE etat_civil');
        $this->addSql('DROP TABLE indicateur_activite');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE objectif');
        $this->addSql('DROP TABLE objectifspecifique');
        $this->addSql('DROP TABLE poste');
        $this->addSql('DROP TABLE prevision');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE resultat_attendu');
        $this->addSql('DROP TABLE resultat_obtenu');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
