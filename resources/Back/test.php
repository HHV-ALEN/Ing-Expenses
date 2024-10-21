//Recipients
        $mail->setFrom('alenstore@alenintelligent.com', 'Solicitud de Viaticos');
        $mail->setFrom('alenstore@alenintelligent.com', 'Solicitud de Viaticos');
        $mail->addAddress($CorreoGerente, $NombreGerente);     //Add a recipient
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Nueva Solicitud de Viático de ' . $Nombre . '';
        $mail->Body = 
        'Se ha hecho el registro de una solicitud de viáticos con la siguiente Información:<br>
        <br>*************************************************************************<br><br>
        Fecha De Salida: ' . $Fecha_SalidaUltimoRegistro . ' <br>
        Hora De Salida: ' . $Hora_SalidaUltimoRegistro . ' <br>
        -------------------------------------<br>
        Fecha De Regreso: ' . $Fecha_RegresoUltimoRegistro . ' <br>
        Hora De Regreso: ' . $Hora_RegresoUltimoRegistro . ' <br>
        -------------------------------------<br>
        Cliente: ' . $ClienteUltimoRegistro . ' <br>
        Motivo: ' . $MotivoUltimoRegistro . ' <br>
        Destino: ' . $Destino . ' <br>
        -------------------------------------<br>
        Monto Total Solicitado: ' . $TotalViaticos . ' <br>
        <br>
        Por favor revisar el sistema para su aprobacion';
        $mail->AltBody = 'Nueva Solicitud de viatico';



        $mail->addAddress($CorreoUsuario, $NombreUsuario);     //Add a recipient
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->CharSet = 'UTF-8';
        $mail->Subject = 'Nueva Solicitud de Viático de ' . $Nombre . '';
        $mail->Body = 
        'Tú Solictud ha sido registrada:<br>
        <br>*************************************************************************<br><br>
        Fecha De Salida: ' . $Fecha_SalidaUltimoRegistro . ' <br>
        Hora De Salida: ' . $Hora_SalidaUltimoRegistro . ' <br>
        -------------------------------------<br>
        Fecha De Regreso: ' . $Fecha_RegresoUltimoRegistro . ' <br>
        Hora De Regreso: ' . $Hora_RegresoUltimoRegistro . ' <br>
        -------------------------------------<br>
        Cliente: ' . $ClienteUltimoRegistro . ' <br>
        Motivo: ' . $MotivoUltimoRegistro . ' <br>
        Destino: ' . $Destino . ' <br>
        -------------------------------------<br>
        Monto Total Solicitado: ' . $TotalViaticos . ' <br>
        <br>
        '. $NombreGerente .' ha sido notificado para su aprobacion';
        $mail->AltBody = 'Nueva Solicitud de viatico';