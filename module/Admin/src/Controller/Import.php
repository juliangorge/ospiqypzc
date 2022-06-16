<?php
namespace Admin\Controller;

class Import
{
    protected $em;
    protected $filename;
    protected $firestore;

    public function __construct($em, $config){
        $this->em = $em;
        $this->filename = $config['exportAfiliadosFile'];

        $this->firestore = new \Google\Cloud\Firestore\FirestoreClient([
            'projectId' => 'ospiqyp-oridhean',
            'keyFilePath' => './ospiqyp-firebase-adminsdk-dx723-21cf738448.json'
        ]);
    }

    // PRE:
    // POST: Construye array con los datos seleccionados de la linea
    private function inicializarLinea(array $linea = []) : array {
        if(!sizeof($linea)) return [];

        return [
            'id' => $linea[0],
            'cuil' => $linea[1],
            'cuil_titular' => $linea[2],
            'parentesco_codigo' => $linea[3],
            'parentesco_nombre' => $linea[4],
            'tipo_documento_codigo' => $linea[5],
            'tipo_documento_nombre' => $linea[6],
            'numero_documento' => $linea[7],
            'nombre' => $linea[8],
            'apellido' => $linea[9],
            'fecha_nacimiento' => $linea[10],
            'tipo_beneficiario_codigo' => $linea[11],
            'tipo_beneficiario_nombre' => $linea[12],
            'situacion_revista_codigo' => $linea[13],
            'situacion_revista_nombre' => $linea[14],
            'estado_civil_codigo' => $linea[15],
            'estado_civil_nombre' => $linea[16],
            'incapacitado_tipo_nombre' => $linea[17],
            'nacionalidad_codigo' => $linea[18],
            'nacionalidad_nombre' => $linea[19],
            'provincia' => $linea[20],
            'localidad' => $linea[21],
            'codigo_postal' => $linea[22],
            'calle' => $linea[23],
            'numero' => $linea[24],
            'piso' => $linea[25],
            'departamento' => $linea[26],
            'telefono_celular' => $linea[27],
            'telefono_laboral' => $linea[28],
            'telefono_particular' => $linea[29],
            'email' => $linea[30],
            'sexo' => $linea[31],
            'numero_afiliado' => $linea[32],
            'gerenciador_codigo' => $linea[33],
            'gerenciador_nombre' => $linea[34],
            'gerenciador_plan' => $linea[35],
            'ultima_fecha_alta' => $linea[36],
            'ultima_fecha_baja' => $linea[37],
            'vencimiento_certificado_estudio' => $linea[38],
            'activo' => $linea[39],
            'ultimo_tipo_movimiento' => $linea[40],
            'tags' => $linea[41],
            'cuit_empleador' => $linea[42],
            'razon_social' => $linea[43]
        ];
    }

    private function procesarLineaAfiliado(array $array) : array {
        $nombre_apellido = $this->procesarNombreApellido($array);

        return [
            'firstname' => $nombre_apellido['firstname'],
            'lastname' => $nombre_apellido['lastname'],
            'dni' => strval((int)$array['numero_documento']),
            'email' => $array['email'],
            'birthday' => $array['fecha_nacimiento'],
            'location' => $array['localidad'],
            'phone_number' => $array['telefono_particular'],
            'affiliate_type' => $this->procesarTipoAfiliado($array['gerenciador_plan']),
            'region_id' => $this->procesarRegion($array['provincia']),
            'is_active' => $array['activo'] == 'Si'
        ];
    }

    private function procesarLineaFamiliar(array $array) : array {
        $nombre_apellido = $this->procesarNombreApellido($array);
        
        return [
            'firstname' => $nombre_apellido['firstname'],
            'lastname' => $nombre_apellido['lastname'],
            'dni' => strval((int)$array['numero_documento']),
            'email' => $array['email'],
            'affiliate_dni' => strval((int)(substr($array['cuil_titular'], -9, 8))),
            'type_of_family_member_id' => $this->procesarTipoFamiliar($array['parentesco_codigo']),
            'phone_number' => $array['telefono_particular'],
            'birthday' => $array['fecha_nacimiento'],
            'region_id' => $this->procesarRegion($array['provincia']),
        ];
    }

    // PRE: Recibe array [nombre, apellido]
    // POST: Devuelve array [nombre, apellido] con el formato correcto
    private function procesarNombreApellido(array $array) : array {
        /* 
        Nombre y apellido se cargan con dos formatos en el mismo csv:
            1. Dos campos, nombre y apellido, separados por comas.
            2. Campo nombre vacio y ambos datos guardados en campo apellido formato 'APELLIDO, NOMBRE'
        */
        if ($array['nombre'] == NULL) {
            $data = explode(', ', $array['apellido']);

            $procesado = ['firstname' => ucwords(strtolower($data[1])), 'lastname' => ucwords(strtolower($data[0]))];

        } else {
            $procesado = ['firstname' => ucwords(strtolower($array['nombre'])), 'lastname' => ucwords(strtolower($array['apellido']))];
        }
        
        return $procesado;
    }

    // PRE: Recibe string
    // POST: Devuelve id tipo afiliado
    private function procesarTipoAfiliado(string $string) : int {

        $id = -1;

        if ($string == 'QUIMI') $id = 0;

        if ($string == 'NOQUIMI') $id = 1;

        if ($string == 'MONO') $id = 2;

        if ($id == -1) throw new \Exception('Tipo de Afiliado invalido. Revisar CSV.');

        return $id;
    }

    // PRE: Recibe string
    // POST: Devuelve id region
    private function procesarRegion(string $string) : int {
        $id = -1;
        
        if ($string == 'Buenos Aires') $id = 1;
        
        if ($string == 'Entre Rios') $id = 2;

        if ($id == -1) throw new \Exception('Tipo de Region invalida. Revisar CSV.');
        
        return $id;
    }

    // PRE: Recibe codigo
    // POST: Devuelve id tipo familiar
    private function procesarTipoFamiliar($codigo) : int {
        $id = -1;
        
        if ($codigo == 1) $id = 1;
        if ($codigo == 2) $id = 3;
        if ($codigo == 3) $id = 2;
        if ($codigo == 4) $id = 4;
        if ($codigo == 8) $id = 5;
        if ($codigo == 9) $id = 6;

        if ($id == -1) throw new \Exception('Tipo de Afiliado Familiar invalido. Revisar CSV.');

        return $id;
    }


    /*
    Todos los affiliates y affiliates_family serán desactivados y activados en la recursión del archivo csv
    Luego se actualizarán los afiliados activos en firebase y se eliminaran los documentos de los afiliados desactivos.
    
    Feature para el futuro:
    Agregar is_active para affiliates_family.

    Ejemplo de error a cubrir:
        Si un afiliado/familiar fue inactivado y nuevamente activado, se debe pisar el anterior registro para respetar la clave única de DNI.

    */
    public function initialize(){
        ini_set('max_execution_time', '30000');
        set_time_limit(30000);
        ini_set('memory_limit', '-1');

        $errors = [];

        try {
            $archivo = fopen($this->filename, 'r');
        }catch(\Throwable $e){
            throw new \Exception('Ocurrió un error en la carga del archivo');
        }

        $this->em->getConnection()->query('UPDATE affiliates SET is_active = false');
        $this->em->getConnection()->query('UPDATE affiliates_family SET is_active = false');

        $ruta = 0;
        $affiliates = [];
        $families = [];

        while(!feof($archivo)){
            $linea = fgetcsv($archivo, 1000, ';','|','~');

            if(!$linea) continue;
            if($linea[0] == NULL) continue;
            if($ruta > 0){
                $data_linea = $this->inicializarLinea($linea);

                // Si es afiliado titular
                if ($data_linea['activo'] == 'Si'){
                    try {
                        if ($data_linea['parentesco_codigo'] == 0) {
                            $data = $this->procesarLineaAfiliado($data_linea);
                            $affiliate = $this->cargarAfiliado($data);
                            if($affiliate != NULL) $affiliates[] = $affiliate;
                        } else {
                            $data = $this->procesarLineaFamiliar($data_linea);
                            $family = $this->cargarFamiliar($data);
                            if($family != NULL) $families[] = $family;
                        }
                    }catch(\Throwable $e){
                        $errors[] = 'Línea ' . $ruta . ' (' . $data_linea['id'] . '): ' . $e->getMessage();
                    }
                }
            }

            $ruta++;
        }

        fclose($archivo);

        try {
            $this->actualizarAfiliadosEnFirebase($affiliates);
        }
        catch(\Throwable $e){
            $errors[] = $e->getMessage();
        }

        try {
            $this->actualizarFamiliaresEnFirebase($families);
        }
        catch(\Throwable $e){
            $errors[] = $e->getMessage();
        }

        return $errors;

    }


    private function actualizarAfiliadosEnFirebase(array $affiliates){
        foreach($affiliates as $affiliate){

            if($affiliate->getIsActive()){

                if($affiliate->getDocumentId()){
                    // Update

                    $docRef = $this->firestore->collection('affiliates_data')->document($affiliate->getDocumentId());
                    $docRef->set($affiliate->toFirebase(), ['merge' => true]);

                    try {
                        $this->em->flush();
                    }
                    catch(\Throwable $e){
                        throw new \Exception('Error al actualizar registro para la APP (Firebase), DNI: ' . $affiliate->getDni());
                    }

                }else{
                    // Create
                    $to_firebase = $affiliate->toFirebase(true);

                    //Agregar a affiliate_dni
                    $this->firestore->collection('affiliates_dni')->add($affiliate->toAffiliateDni());

                    $documentReference = $this->firestore->collection('affiliates_data')->add($to_firebase);
                    $affiliate->setDocumentId($documentReference->id());

                    try {
                        $this->em->flush();
                    }
                    catch(\Throwable $e){
                        throw new \Exception('Error al crear registro para la APP (Firebase), DNI: ' . $affiliate->getDni());
                    }
                }

            }else{

                $docRef = $this->firestore->collection('affiliates_data')->document($affiliate->getDocumentId());
                $docRef->set(['active_user' => false], ['merge' => true]);

            }
        }
    }

    private function actualizarFamiliaresEnFirebase(array $families){
        foreach($families as $family){

            //if($family->getIsActive()){

                if($family->getDocumentId()){
                    // Update
                    $docRef = $this->firestore->collection('affiliates_family')->document($family->getDocumentId());
                    
                    $to_firebase = $family->toFirebase();
                    $to_firebase['affiliate_number'] = $this->obtenerCredencialFamiliar($to_firebase);
                    $to_firebase['type_of_family_member'] = $this->obtenerMiembroFamiliar($to_firebase);
                    unset($to_firebase['type_of_family_member_id']);
                    $docRef->set($to_firebase, ['merge' => true]);

                    try {
                        $this->em->flush();
                    }
                    catch(\Throwable $e){
                        throw new \Exception('Error al actualizar registro para la APP (Firebase), DNI: ' . $family->getDni());
                    }

                }else{
                    // Create
                    $to_firebase = $family->toFirebase(true);
                    $to_firebase['affiliate_number'] = $this->obtenerCredencialFamiliar($to_firebase);
                    $to_firebase['type_of_family_member'] = $this->obtenerMiembroFamiliar($to_firebase);
                    unset($to_firebase['type_of_family_member_id']);

                    $documentReference = $this->firestore->collection('affiliates_family')->add($to_firebase);
                    $family->setDocumentId($documentReference->id());

                    try {
                        $this->em->flush();
                    }
                    catch(\Throwable $e){
                        throw new \Exception('Error al crear registro para la APP (Firebase), DNI: ' . $family->getDni());
                    }
                }

            //}else{

                //$docRef = $this->firestore->collection('affiliates_family')->document($family->getDocumentId());
                //$docRef->set(['is_active' => false], ['merge' => true]);


            //}
        }
    }

    private function cargarAfiliado(array $array){
        // Verifico si existe un afiliado con el numero de documento
        // Si existe actualizo
        // Si no, lo creo.

        $entity = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $array['dni']]);

        if($entity == NULL){
            // Creacion
            $entity = new \Admin\Entity\Affiliate();
            $entity->fromImport($array);
            $this->em->persist($entity);

            try {
                $this->em->flush();
            }
            catch(\Throwable $e){
                throw new \Exception('Error al crear registro DNI: ' . $array['dni']);
            }

        }else{

            // Actualizo campo a campo para no pegarle a firebase siempre
            $entity_tmp = [
                'firstname' => $entity->getFirstname(),
                'lastname' => $entity->getLastname(),
                'dni' => $entity->getDni(),
                'email' => $entity->getEmail(),
                'birthday' => $entity->getBirthday()->format('d/m/Y'),
                'location' => $entity->getLocation(),
                'phone_number' => $entity->getPhoneNumber(),
                'affiliate_type' => $entity->getAffiliateType(),
                'region_id' => $entity->getRegionId(),
                //'is_active' => $entity->getIsActive()
            ];

            if($entity_tmp == $array) return NULL;

            $entity->fromImport($array);

            try {
                $this->em->flush();
            }
            catch(\Throwable $e){
                throw new \Exception('Error al actualizar registro DNI: ' . $array['dni']);
            }

        }

        return $entity;
    }

    private function cargarFamiliar(array $array){
        // Verifico si existe un familiar con el numero de documento
        // Si existe actualizo
        // Si no, lo creo.

        $entity = $this->em->getRepository('Admin\Entity\AffiliateFamily')->findOneBy(['dni' => $array['dni']]);

        if($entity == NULL){
            // Creo
            $entity = new \Admin\Entity\AffiliateFamily();
            $entity->fromImport($array);
            $this->em->persist($entity);

            try {
                $this->em->flush();
            }
            catch(\Throwable $e){
                throw new \Exception('Error al crear registro DNI: ' . $array['dni']);
            }

        }else{
            // Actualizo campo a campo para no pegarle a firebase siempre

            $entity_tmp = [
                'firstname' => $entity->getFirstname(),
                'lastname' => $entity->getLastname(),
                'dni' => $entity->getDni(),
                'email' => $entity->getEmail(),
                'affiliate_dni' => $entity->getAffiliateDni(),
                'type_of_family_member_id' => $entity->getTypeOfFamilyMemberId(),
                'phone_number' => $entity->getPhoneNumber(),
                'birthday' => $entity->getBirthday()->format('d/m/Y'),
                'region_id' => $entity->getRegionId()
            ];

            if($entity_tmp == $array) return NULL;

            $entity->fromImport($array);

            try {
                $this->em->flush();
            }
            catch(\Throwable $e){
                throw new \Exception('Error al actualizar registro DNI: ' . $array['dni']);
            }
        }

        return $entity;
    }

    private function obtenerMiembroFamiliar(array $array){
        $family_member = $this->em->find('Admin\Entity\TypeOfFamilyMember', $array['type_of_family_member_id']);
        if($family_member == NULL) throw new \Exception('Error al cargar Tipo de Miembro Familiar registro DNI: ' . $array['dni']);

        return $family_member->getName();
    }

    private function obtenerCredencialFamiliar(array $array){
        $affiliate_number = $this->em->getRepository('Admin\Entity\Affiliate')->findOneBy(['dni' => $array['affiliate_dni']]);
        if($affiliate_number == NULL) throw new \Exception('Error al cargar Credencial Familiar registro DNI: ' . $array['dni']);

        return $affiliate_number->getAffiliateNumber();
    }

}