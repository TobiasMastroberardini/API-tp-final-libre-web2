<?php
class Model
{
  protected $db;

  function __construct()
  {
    try {
      $this->db = new PDO('mysql:host=' . MYSQL_HOST . ';dbname=' . MYSQL_DB . ';charset=utf8', MYSQL_USER, MYSQL_PASS);
    } catch (\Throwable $th) {
      $this->createDB();
    }
    $this->deploy();
  }

  function createDB()
  {
    $this->db = new PDO('mysql:host=' . MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
    $DBName = MYSQL_DB;

    $sql = <<<END
            SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
            START TRANSACTION;
            SET time_zone = "+00:00";
        
            CREATE DATABASE IF NOT EXISTS `$DBName` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
            USE `$DBName`;
          END;

    $this->db->query($sql);
  }

  function deploy()
  {
    // Chequear si hay tablas
    $query = $this->db->query('SHOW TABLES');
    $tables = $query->fetchAll(); // Nos devuelve todas las tablas de la db
    if (count($tables) == 0) {
      // Si no hay crearlas
      $sql = <<<END
                /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
                /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
                /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
                /*!40101 SET NAMES utf8mb4 */;



                CREATE TABLE `prestadores` (
                  `prestadorId` int(11) NOT NULL,
                  `fechaCreacion` date NOT NULL,
                  `origen` varchar(45) NOT NULL,
                  `nombrePrestador` varchar(45) NOT NULL
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


                  INSERT INTO `prestadores` (`prestadorId`, `fechaCreacion`, `origen`, `nombrePrestador`) VALUES
                  (1, '2003-03-11', 'Argentina', 'Rivadavia'),
                  (2, '1952-12-30', 'EEUU', 'JPmorgan'),
                  (3, '1975-01-20', 'Brasil', 'BRSeguros');



                  CREATE TABLE `reviews` (
                    `reviewId` int(11) NOT NULL,
                    `descripcion` varchar(200) NOT NULL,
                    `puntuacion` int(11) NOT NULL,
                    `usuario` varchar(200) NOT NULL,
                    `seguroId` int(11) NOT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



                    INSERT INTO `reviews` (`reviewId`, `descripcion`, `puntuacion`, `usuario`, `seguroId`) VALUES
                    (1, 'Buena atencion al cliente y buen precio.', 7, 'webadmin', 1),
                    (2, 'Buena experiencia cobrando seguro', 8, 'webadmin', 2),
                    (3, 'Tardaron mucho enn pagar mi seguro', 4, 'webadmin', 3);


                    CREATE TABLE `seguros` (
                      `seguroId` int(11) NOT NULL,
                      `nombreSeguro` varchar(45) NOT NULL,
                      `fechaLanzamiento` date NOT NULL,
                      `prestadorId` int(11) NOT NULL,
                      `descripcionSeguro` text NOT NULL,
                      `coberturaMaxima` int(11) NOT NULL,
                      `imagen` varchar(200) NOT NULL
                      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



                      INSERT INTO `seguros` (`seguroId`, `nombreSeguro`, `fechaLanzamiento`, `prestadorId`, `descripcionSeguro`, `coberturaMaxima`, `imagen`) VALUES
                      (1, 'Rivadavia500', '2019-10-24', 1, 'Seguro con cobertura contra 3ros y granizo.', 80000000, 'https://images.app.goo.gl/VGVeSr2wno9K7h2d9'),
                      (2, 'JPMorgan1500', '2021-11-26', 2, 'Seguro con cobertura contra todo riesgo', 25, 'https://images.app.goo.gl/rgkWBRgoiZtdtcfTA'),
                      (3, 'BR300', '2003-10-30', 3, 'Posee una Cobertura contra 3ros, robo e incendio', 30000000, 'https://images.app.goo.gl/BawJw94tJbw31xwY7');



                      CREATE TABLE `usuarios` (
                        `usuario` varchar(200) NOT NULL,
                        `contrasenia` varchar(200) NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


                        INSERT INTO `usuarios` (`usuario`, `contrasenia`) VALUES
                        ('webadmin', '$2y$10$5iX0BZS3E2qRR090rtKUqOfAc0XDL6XFpVFBpWODBWxIsw/t65DRq');


                        ALTER TABLE `prestadores`
                        ADD PRIMARY KEY (`prestadorId`);

                        ALTER TABLE `reviews`
                        ADD PRIMARY KEY (`reviewId`),
                        ADD KEY `seguroId` (`seguroId`),
                        ADD KEY `usuario` (`usuario`) USING BTREE;


                        ALTER TABLE `seguros`
                        ADD PRIMARY KEY (`seguroId`),
                        ADD KEY `prestador` (`prestadorId`) USING BTREE;

                        ALTER TABLE `usuarios`
                        ADD PRIMARY KEY (`usuario`);


                        ALTER TABLE `reviews`
                        MODIFY `reviewId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;


                        ALTER TABLE `reviews`
                        ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`usuario`) REFERENCES `usuarios` (`usuario`),
                        ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`seguroId`) REFERENCES `seguros` (`seguroId`);


                        ALTER TABLE `seguros`
                         ADD CONSTRAINT `seguros` FOREIGN KEY (`prestadorId`) REFERENCES `prestadores` (`prestadorId`);
                         COMMIT;

                         /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
                         /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
                         /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

                END;
      $this->db->query($sql);
    }
  }
}
