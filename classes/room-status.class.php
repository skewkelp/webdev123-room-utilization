<?php

require_once 'database.class.php';

class RoomStatus{
    public $semester = '';
    public $school_year = '';

    
    //PK ROOM 
    public $room_code = '';
    public $room_no = '';
    
    //room_list
    public $room_name = '';
    public $room_type = '';
    
    //subject_Details PK
    public $subject_code = '';
    public $description = '';
    public $total_units = '';
    public $lec_units = '';
    public $lab_units = '';

    public $prospectus_id = '';
    
    
    public $class_name = '';
    public $status = '';
    
    // Properties for class details
    public $class_id = ''; // PK class_details
    public $subject_type = '';//PK

    public $subject_id = '';

    public $section_id = '';
    public $course_abbr = '';
    public $year_level = '';
    public $section = '';
    
    public $teacher_assigned = ''; 
    public $room_id = '';
    
    public $original_class_id = '';
    public $original_subject_type = '';
    public $original_class_day = '';
    public $original_subject_id = '';
    
    public $start_time = '';
    public $end_time = ''; 
    public $day_id = ''; //day name = 'Monday','Tuesday', etc
    
    public $class_status_id = '';
    
    // Properties for IDs and logs
    public $class_time_id = ''; // PK class_time
    public $class_day_id = '';
    public $log_cid = []; // Log for class IDs
    public $log_ctid = []; // Log for class time IDs
    public $log_cdid = []; // Log for class day IDs
    public $log_day = []; // Log for day IDs
    public $log_sid = []; // Log for day IDs
    public $week_day = '';
    public $id = '';
    
    protected $db;
    
    function __construct(){
        $this->db = new Database();
    }
    
    
    //NEW QUERIES 
    function updateSubjectDetails($originalSubjectCode){
        $sql = "UPDATE subject_details 
            SET subject_code = :subject_code,
                `description` = :description, 
                lec_units = :lec_units, 
                lab_units = :lab_units, 
                total_units = :total_units
                
            WHERE subject_code = :originalSubjectCode AND subject_prospectus_id = :prospectus_id;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_code', $this->subject_code);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':lec_units', $this->lec_units);
        $query->bindParam(':lab_units', $this->lab_units);
        $query->bindParam(':total_units', $this->total_units);
        $query->bindParam(':prospectus_id', $this->prospectus_id);
        $query->bindParam(':originalSubjectCode', $originalSubjectCode);
        $query->execute();
        return true;
    }


    function updateClassDetails(){
        $sql = "UPDATE class_details 
            SET class_id = :class_id,
                subject_type = :subject_type, 
                subject_id = :subject_id, 
                course_abbr = :course_abbr, 
                year_level = :year_level, 
                section = :section,
                teacher_assigned = :teacher_id
                
            WHERE class_id = :original_class_id AND subject_type = :original_subtype_id;";
            
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':subject_id', $this->subject_id);

        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);

        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $query->bindParam(':original_class_id', $this->original_class_id);
        $query->bindParam(':original_subtype_id', $this->original_subject_type);
        $query->execute();
        return true;
    }

    //UPDATE CLASS SCHEDULE
    function updateScheduleDay(){
        $sql = "UPDATE class_schedule
            SET class_id = :class_id,
                subject_type = :subject_type, 
                `day` = :day_id, 
                start_time = :start_time, 
                end_time = :end_time, 
                room_code = :room_code, 
                room_no = :room_no
            WHERE class_id = :originalClassID AND subject_type = :originalSubtype And `day` = :originalClassDay
        ;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':day_id', $this->day_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':room_code', $this->room_code);
        $query->bindParam(':room_no', $this->room_no);
        $query->bindParam(':originalClassID', $this->original_class_id);
        $query->bindParam(':originalSubtype', $this->original_subject_type);
        $query->bindParam(':originalClassDay', $this->original_class_day);
        
        if ($query->execute()) {
            return true;
        } else {
            // Log error information
            error_log("Update failed: " . implode(", ", $query->errorInfo()));
            return false; // Update failed
        }
    }
 
    function insertSubjectDetails(){

        $sql = "INSERT INTO subject_details (subject_code, `description`, total_units, lec_units, lab_units, subject_prospectus_id) VALUES (:subject_code, :description, :total_units, :lec_units, :lab_units, :prospectus_id);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_code', $this->description);
        $query->bindParam(':description', $this->description);
        $query->bindParam(':total_units', $this->total_units);
        $query->bindParam(':lec_units', $this->lec_units);
        $query->bindParam(':lab_units', $this->lab_units);
        $query->bindParam(':prospectus_id', $this->prospectus_id);
 
        $query->execute();
        return true;
    }


    //UPDATED 
    function insertClassDetails(){

        $sql = "INSERT INTO class_details (class_id, subject_type, subject_id, course_abbr, year_level, section, teacher_assigned, semester, school_year) VALUES (:class_id, :subject_type, :subject_id, :course_abbr, :year_level, :section, :teacher_id, :semester, :school_year);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        $query->bindParam(':teacher_id', $this->teacher_assigned);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->execute();
        return true;
    }

    function insertScheduleDay(){
        $sql = "INSERT INTO class_schedule 
        (class_id, subject_type, `day`, start_time, end_time, room_code, room_no, semester, school_year) 
        VALUES (:class_id, :subject_type, :day_id, :start_time, :end_time, :room_code, :room_no, :semester, :school_year);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':day_id', $this->day_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam('room_code', $this->room_code);
        $query->bindParam('room_no', $this->room_no);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->execute();
        return true;
    }


    
    function insertClassTime(){
        $sql = "INSERT INTO class_time (class_id, subject_id, start_time, end_time) VALUES (:class_id, :subject_id, :start_time, :end_time);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->execute();
        $this->class_time_id = $this->db->connect()->lastInsertId();

        return $this->class_time_id;
    }

    function insertClassDay(){
        $sql = "INSERT INTO class_day (class_time_id, day_id) VALUES (:class_time_id, :day_id);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_time_id', $this->class_time_id);
        $query->bindParam(':day_id', $this->day_id);
        $query->execute();
        $this->class_day_id = $this->db->connect()->lastInsertId();

        $this->insertStatus();

        return true;
    }

    function insertStatus(){
        $sql = "INSERT INTO scheduled_statuses (class_day_id, semester, school_year) VALUES (:class_day_id, :semester, :school_year);";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_day_id', $this->class_day_id);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->execute();

        return true;
    }


    
    public $original_day_id = '';

   
    function showTeacherSchedule(){
        $sql = "SELECT
                stat.class_day_id AS class_status_id,
                cday.id AS cday_id,
                d.day AS week_day,
                room.room_name AS room_name,
                rtype.room_description AS room_type,
                sub.subject_code AS subject_code,
                stdesc.type AS subject_type,
                sec.section_name AS section_name,
                ctime.start_time AS start_time,
                ctime.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                sdesc.description AS room_status

            FROM
                semester sem
            LEFT JOIN 
                scheduled_statuses stat ON sem.semester = stat.semester AND sem.school_year = stat.school_year
            LEFT JOIN 
                status_description sdesc ON stat.status_desc_id = sdesc.id
            LEFT JOIN 
                class_day cday ON stat.class_day_id = cday.id
            LEFT JOIN
                _day d ON cday.day_id = d.id
            LEFT JOIN
                class_time ctime ON cday.class_time_id = ctime.id
            LEFT JOIN
                class_details class ON ctime.class_id = class.id AND ctime.subject_id = class.subject_id
            LEFT JOIN
                room_list room ON class.room_id = room.id
            LEFT JOIN
                room_type rtype ON room.type_id = rtype.id
            LEFT JOIN
                section_details sec ON class.section_id = sec.id
            LEFT JOIN
                course_details course ON sec.course_id = course.id
            LEFT JOIN
                subject_details sub ON class.subject_id = sub.id
            LEFT JOIN
                subject_type_description stdesc ON sub.type_id = stdesc.id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.id
            LEFT JOIN 
                account acc ON fac.account_id = acc.id
                
            WHERE sem.semester = :semester AND sem.school_year = :school_year
            AND class.teacher_assigned = :teacher_id
        
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);
        $query->bindParam(':teacher_id', $this->teacher_id);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll();
        }
        return $data;


    }

    function showAllSubjectDetails($selectedProspectus = null){
        $sql =
            "SELECT
                sub.subject_code AS subject_id, 
                sub.description AS sub_desc, 
                sub.total_units AS total_units,
                sub.lec_units AS lec_units,
                sub.lab_units AS lab_units

            FROM subject_details sub 
            LEFT JOIN subject_prospectus prosp 
            ON sub.subject_prospectus_id = prosp.effective_school_year  
            
        ";
    
        if($selectedProspectus !== null){
            $sql .= " WHERE sub.subject_prospectus_id = :selectedProspectus ORDER BY subject_id;";

        }else{
            $sql .= "  ORDER BY subject_id;";
        }
        
        $query = $this->db->connect()->prepare($sql);

        if($selectedProspectus !== null){
            $query->bindParam(':selectedProspectus', $selectedProspectus);
        }

        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }


    function showAllStatus($selectedDay = null){
        $sql = 
            "SELECT
                sched.day AS class_day,
                sched.class_id AS class_id,
                sched.subject_type AS subject_type,
                sched.day AS class_day,

                CONCAT(sched.room_code, ' ', sched.room_no) AS room_name,
                rtype.room_description AS room_type,

                class.subject_id AS subject_code,

                CONCAT(class.course_abbr, class.year_level, class.section) AS section_name,

                sched.start_time AS start_time,
                sched.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                
                sched.status AS room_status,
                sched.remarks AS remarks

            FROM
                semester sem
            LEFT JOIN 
                class_schedule sched ON sem.semester = sched.semester AND sem.school_year = sched.school_year
            LEFT JOIN 
                class_details class ON sched.class_id = class.class_id  AND sched.subject_type = class.subject_type
            LEFT JOIN
                room_list room ON sched.room_code = room.room_code AND sched.room_no = room.room_no
            LEFT JOIN
                room_type rtype ON room.room_code = rtype.room_type_id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN 
                user_list user ON fac.user_id = user.user_id
            LEFT JOIN 
                account acc ON user.user_id = acc.account_id
        
                
            WHERE sem.semester = :semester AND sem.school_year = :school_year 
        
        ";

        if($selectedDay !== null){
            $sql .= " AND sched.day = :selectedDay ORDER BY room_name, start_time;";

        }else{
            $sql .= " ORDER BY room_name, start_time;";
        }
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);

        if($selectedDay !== null){
            $query->bindParam(':selectedDay', $selectedDay);
        }

        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }


    function showAllClassDetails(){
        $sql = 
            "SELECT 
                class.class_id AS class_id,
                class.subject_type AS subject_type,
                CONCAT (class.class_id ,'|', class.subject_type) AS id,
                CONCAT(class.subject_id,' ', class.subject_type) AS subject_, 
                CONCAT(class.course_abbr, class.year_level, class.section) AS section_, 
                CONCAT(acc.last_name,', ',acc.first_name) AS teacher_ 

            FROM class_details class 
            LEFT JOIN section_details sec ON class.course_abbr = sec.course_abbr AND class.year_level = sec.year_level AND class.section = sec.section
            LEFT JOIN subject_details sub ON class.subject_id = sub.subject_code
            LEFT JOIN faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN user_list user ON fac.user_id = user.user_id
            LEFT JOIN account acc ON user.user_id = acc.account_id
            LEFT JOIN semester sem ON class.semester = sem.semester

            WHERE sem.semester = :semester AND sem.school_year = :school_year ORDER BY section_
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':semester', $this->semester);
        $query->bindParam(':school_year', $this->school_year);

        $data = null;
        if ($query->execute()){
            $data = $query->fetchAll();
        }
        return $data;
    }
    
    //check if subject has a LEC and LAB units
    function checkSubjectType($subject_id, $type){
        $sql = "SELECT subject_code, lec_units, lab_units
            FROM subject_details sub
            WHERE subject_code = :subject_code ";
        
        if ($type == "LEC") {
            $sql .= " AND lec_units = 0;";
        } else if ($type == "LAB") {
            $sql .= " AND lab_units = 0;";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_code', $subject_id);

        if ($query->execute()) {
            $data = $query->fetch();

            if ($type == "LEC") {
                return $data ? $data['lab_units'] : null; // Return lab_units if type is LEC
            } else if ($type == "LAB") {
                return $data ? $data['lec_units'] : null; // Return lec_units if type is LAB
            }
        }
        return null;
    }


    function checkExistingSubjectCode($subjectID, $prospectusID, $excludeSubjectID = null, $excludeProspectusID = null){
        $sql = "SELECT subject_code
            FROM subject_details
            WHERE subject_code = :subjectID AND subject_prospectus_id = :prospectusID";

        // Only add the exclusion condition if $excludeID is provided
        if ($excludeSubjectID !== null && $excludeProspectusID !== null) {
            $sql .= " AND (subject_code != :excludeSubjectID AND subject_prospectus_id != :excludeProspectusID)"; // Assuming subject_id is the primary key
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subjectID', $subjectID);
        $query->bindParam(':prospectusID', $prospectusID);
        
        if ($excludeSubjectID !== null && $excludeProspectusID !== null) {
            $query->bindParam(':excludeSubjectID', $excludeSubjectID);
            $query->bindParam(':excludeProspectusID', $excludeProspectusID);
        }

        $data = null;

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? $data['subject_code'] : null;  // Return data subject code if ID found
        }
        return null;
    }



    //cannot have same class id and subject id
    function checkExistingClassDetailsPK($recordID){
        $sql = "SELECT 
            class.class_id AS class_id, 
            CONCAT(class.subject_id,' ', class.subject_type) AS subject_,
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_
        FROM class_details class
        
        WHERE class.class_id = :class_id
        AND (class.subject_id = :subject_id AND class.subject_type)
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $recordID);
        $query->bindParam(':subject_id', $this->subject_id);
        
        if ($query->execute()) {
            $data = $query->fetch();
            return $data ?['class_id' => $data['class_id'], 'section_' => $data['section_'], 'subject_' => $data['subject_']] : null;  // Return just the ID if found
        }
        return null;
    }

    function checkClassSubtypeExisting($classID, $subType, $excludeClassID = null, $excludeSubType = null){
        $sql = "SELECT c.subject_type AS stype, s.lec_units AS lec_units, s.lab_units AS lab_units
            FROM class_details c LEFT JOIN subject_details s ON c.subject_id = s.subject_code
            WHERE c.class_id = :class_id AND c.subject_type = :subType ";

        if($excludeClassID !== null && $excludeSubType !== null){
        $sql .= 'AND (c.class_id != :excludeClassID AND c.subject_type != :excludeSubType);';
        }


        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $classID);
        $query->bindParam(':subType', $subType);

        if($excludeClassID !== null && $excludeSubType !== null){
            $query->bindParam(':excludeClassID', $excludeClassID);   
            $query->bindParam(':excludeSubType', $excludeSubType);   
        }

        if ($query->execute()) {
            $data = $query->fetch();

            if(!empty($data) && $data['stype'] == 'LEC'){
                return $data ? ['lec_units' => $data['lec_units'], 'lab_units' => $data['lab_units']] : null;
            }

            if(!empty($data) && $data['stype'] == 'LAB'){
                return $data ? ['lec_units' => $data['lec_units'], 'lab_units' => $data['lab_units']] : null;
            }
        }
        return null;
    }

    function alternateClassSubtype($classID){
        $sql = "SELECT subject_type
        FROM class_details 
        WHERE class_id = :class_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $classID);
        $query->execute();
        $data = $query->fetch();
        return $data? $data['subject_type'] : null;
    }
    

 
    //CHECK IF EXISTING SUBJECT EXIST ON A SECTION
    function checkSubjectSectionExisting($excludeID){
        $sql = "SELECT class.class_id AS class_id
        FROM class_details class
        WHERE (class.subject_id = :subject_id AND class.subject_type = :subject_type) AND (class.course_abbr = :course_abbr AND class.year_level = :year_level AND class.section = :section)";
        
        if($excludeID !== null){
            $sql .= " AND class.class_id != :excludeID";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        
        if($excludeID !== null){
            $query->bindParam(':excludeID', $excludeID);   
        }

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? $data['class_id'] : null;
        }
        return null;
    }

    function checkClassIDExisting($recordID, $excludeID = null){
        $sql = "SELECT 
            DISTINCT class.class_id AS class_id, 
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_

        FROM class_details class 

        WHERE class.class_id = :class_id
        ";

        if($excludeID !== null){
            $sql .= " AND class.class_id != :excludeID;";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $recordID);
        
        if($excludeID !== null){
            $query->bindParam(':excludeID', $excludeID);
        }

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? $data['section_'] : null; 
        }
        return null;

    }


    //CHECK IF EXISTING SUBJECT_NAME AND SECTION_NAME ALREADY EXIST, condition for class id when it can be unique
    function checkConditionClassDetailPK(){
        $sql = "SELECT
            DISTINCT class.subject_id AS subject_id,
            class.class_id AS class_id,
            CONCAT(class.course_abbr, class.year_level, class.section) AS section_name

        FROM class_details class

        WHERE class.subject_id = :subject_id AND (class.course_abbr = :course_abbr AND class.year_level = :year_level AND class.section = :section)
        ;";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_id', $this->subject_id);
        $query->bindParam(':course_abbr', $this->course_abbr);
        $query->bindParam(':year_level', $this->year_level);
        $query->bindParam(':section', $this->section);
        $query->execute();
        $data = $query->fetch();
        return $data ? ['class_id' => $data['class_id'], 'subject_id' => $data['subject_id'], 'section_name' => $data['section_name']] : null;
    }

 
    //CHECK IF AN EXISTING TIME ROW ALREADY EXIST ON TABLE
    function checkExistingClassTime($excludeClassID = null, $excludeSubtype = null, $excludeDay = null){
        $sql = "SELECT      
        s.class_id AS class_id,
        s.subject_type AS sub_type,
        s.day AS day_name,
        s.start_time AS start_time,
        s.end_time AS end_time,
        CONCAT(s.room_code, ' ', s.room_no) AS room

        FROM class_schedule s

        WHERE s.day = :day_id AND (
        s.room_code = :room_code AND s.room_no = :room_no
        ) AND (
        (s.start_time <= :end_time AND s.end_time >= :start_time)
        OR (s.start_time >= :start_time AND s.start_time < :end_time)
        OR (s.end_time > :start_time AND s.end_time <= :end_time)
        )";

        if ($excludeClassID && $excludeSubtype && $excludeDay){
            $sql .= " AND (s.class_id != :excludeClassID AND s.subject_type != :excludeSubtype AND s.day != :excludeDay)";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':start_time', $this->start_time);
        $query->bindParam(':end_time', $this->end_time);
        $query->bindParam(':day_id', $this->day_id);
        $query->bindParam(':room_code', $this->room_code);
        $query->bindParam(':room_no', $this->room_no);
        
        if ($excludeClassID !== null && $excludeSubtype !== null && $excludeDay !== null) {
            $query->bindParam(':excludeClassID', $excludeClassID);
            $query->bindParam(':excludeSubtype', $excludeSubtype);
            $query->bindParam(':excludeDay', $excludeDay);
        }
        
        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? [$data['class_id'], $data['day_name'], $data['start_time'], $data['end_time'], $data['room']] : null;
        } 
        return null;
    }

    //CHECK IF CLASS ID ON DAY EXIST, CHECKS IF ROW DATA EXIST ON CLASS_SCHEDULE
    function checkClassDayAlreadyExist($excludeClassID = null, $excludeSubtype = null, $excludeDay = null){
        $sql = "SELECT      
        s.class_id AS class_id,
        s.subject_type AS sub_type,
        s.day AS day_name,
        s.start_time AS start_time,
        s.end_time AS end_time,
        CONCAT(s.room_code, ' ', s.room_no) AS room

        FROM class_schedule s

        WHERE s.class_id = :class_id AND s.subject_type = :subject_type AND s.day = :class_day";

        if ($excludeClassID !== null && $excludeSubtype !== null && $excludeDay !== null){
            $sql .= " AND (s.class_id != :excludeClassID AND s.subject_type != :excludeSubtype AND s.day != :excludeDay)";
        }

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':class_day', $this->day_id);

        if ($excludeClassID !== null && $excludeSubtype !== null && $excludeDay !== null) {
            $query->bindParam(':excludeClassID', $excludeClassID);
            $query->bindParam(':excludeSubtype', $excludeSubtype);
            $query->bindParam(':excludeDay', $excludeDay);
        }

        if ($query->execute()) {
            $data = $query->fetch();
            return $data ? [$data['class_id'], $data['day_name'], $data['start_time'], $data['end_time'], $data['room']] : null;
        } 
        return null;
      
    }

    function fetchsubjectdetailsRecord($subjectCode, $prospectusID){
        $sql =
            "SELECT
                sub.subject_code AS subject_id, 
                sub.description AS sub_desc, 
                sub.total_units AS total_units,
                sub.lec_units AS lec_units,
                sub.lab_units AS lab_units,
                sub.subject_prospectus_id AS prospectus_id

            FROM subject_details sub 
            LEFT JOIN subject_prospectus prosp 
            ON sub.subject_prospectus_id = prosp.effective_school_year  

            WHERE sub.subject_code = :subjectCode AND sub.subject_prospectus_id = :prospectusID;
        ";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subjectCode', $subjectCode);
        $query->bindParam(':prospectusID', $prospectusID);

        $data = null;

        if ($query->execute()){
            $data = $query->fetch();
        }
        return $data;
    }

    function fetchclassDetailsRecord($classID, $subType){
        // $sql = "SELECT * FROM class_details WHERE id = :recordID;";
        $sql = 
            "SELECT 
                class.class_id AS class_id,
                class.subject_type AS subtype_id,

                class.subject_id AS subject_id,
                CONCAT(sub.lec_units,'|',sub.lab_units) AS subject_units,

                CONCAT(class.course_abbr,'|', class.year_level,'|', class.section) AS section_id,
                CONCAT(class.course_abbr, class.year_level, class.section) AS section_name, 
                
                class.teacher_assigned AS teacher_id,
                CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name 

            FROM class_details class 
            LEFT JOIN section_details sec ON class.course_abbr = sec.course_abbr AND class.year_level = sec.year_level AND class.section = sec.section
            LEFT JOIN subject_details sub ON class.subject_id = sub.subject_code
            LEFT JOIN faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN user_list user ON fac.user_id = user.user_id
            LEFT JOIN account acc ON user.user_id = acc.account_id
            
            WHERE class.class_id = :classID AND class.subject_type = :subType
        ;";
        
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':classID', $classID);
        $query->bindParam(':subType', $subType);

        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function fetchroomstatusRecord($recordClassID, $recordSubType, $recordClassDay){
        $sql = 
            "SELECT
                sched.class_id AS class_id,
                sched.subject_type AS subject_type,
                sched.day AS class_day,

                class.subject_id AS subject_code,
                CONCAT(sub.lec_units,'|',sub.lab_units) AS subject_units,

                CONCAT(class.course_abbr, class.year_level, class.section) AS section_name,

                sched.start_time AS start_time,
                sched.end_time AS end_time,
                CONCAT(acc.last_name,', ',acc.first_name) AS faculty_name,
                
                CONCAT(sched.room_code, ' ', sched.room_no) AS room_name,
                CONCAT(sched.room_code,'|',sched.room_no) AS room_id,

                rtype.room_description AS room_type,
                
                sched.status AS room_status,
                sched.remarks AS remarks

            FROM
                semester sem
            LEFT JOIN 
                class_schedule sched ON sem.semester = sched.semester AND sem.school_year = sched.school_year
            LEFT JOIN 
                class_details class ON sched.class_id = class.class_id  AND sched.subject_type = class.subject_type
            LEFT JOIN
                subject_details sub ON class.subject_id = sub.subject_code
            LEFT JOIN
                room_list room ON sched.room_code = room.room_code AND sched.room_no = room.room_no
            LEFT JOIN
                room_type rtype ON room.room_code = rtype.room_type_id
            LEFT JOIN
                faculty_list fac ON class.teacher_assigned = fac.faculty_id
            LEFT JOIN 
                user_list user ON fac.user_id = user.user_id
            LEFT JOIN 
                account acc ON user.user_id = acc.account_id
        
                
            WHERE sched.class_id = :recordClassID AND sched.subject_type= :recordSubType AND sched.day= :recordClassDay
        
        ;";
      
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordClassID', $recordClassID);
        $query->bindParam(':recordSubType', $recordSubType);
        $query->bindParam(':recordClassDay', $recordClassDay);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }

    function deleteSubjectDetails(){
        $sql = "DELETE FROM subject_details WHERE subject_code = :subject_code AND subject_prospectus_id = :subject_prospectus_id;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':subject_code', $this->subject_code);
        $query->bindParam(':subject_prospectus_id', $this->prospectus_id);
        $query->execute();
        return true;
    }

    function deleteClassDetails(){
        $sql = "DELETE FROM class_details WHERE class_id = :class_id AND subject_type = :subject_type;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->execute();
        return true;
    }

    function deleteClassSchedule(){
        $sql = "DELETE FROM class_schedule WHERE class_id = :class_id AND subject_type = :subject_type AND `day` = :class_day
        ;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':class_id', $this->class_id);
        $query->bindParam(':subject_type', $this->subject_type);
        $query->bindParam(':class_day', $this->day_id);
        $query->execute();

        return true;
    }

    function fetchRoomName($recordID){
        $sql = "SELECT room_name FROM room_list WHERE id = :recordID;";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':recordID', $recordID);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetch();
        }
        return $data;
    }
    
    function roomnameExists($room_name, $excludeID = null){
        $sql = "SELECT COUNT(*) FROM room_list WHERE room_name = :room_name";
        if ($excludeID !== null) {
            $sql .= " AND id != :excludeID";
        }
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':room_name', $room_name);
        if ($excludeID !== null) {
            $query->bindParam(':excludeID', $excludeID);
        }
        $query->execute();
        $count = $query->fetchColumn();
        return $count > 0;
    }

    //fetch room type for dropdown
    public function fetchroomType(){
        $sql = 
            "SELECT room_type_id as type_id, room_description AS rtype_desc 
            FROM room_type
            ORDER BY rtype_desc ASC;
        
        ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown, room_name in room list
    public function fetchroomList(){
        $sql = " SELECT *, CONCAT(room_code, ' ', room_no) AS room_name FROM room_list;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


    //for filter dropdown status list
    public function fetchstatusOption(){
        $sql = " SELECT sd.description AS status_desc FROM status_description sd;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


    public function fetchprospectusOption(){
        $sql = 
        " SELECT prosp.effective_school_year AS prospectus_id

        FROM subject_prospectus prosp;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }


    //for filter dropdown search subject code
    public function fetchsubjectOption($prospectusID = null){
        $sql = 
        " SELECT sub.subject_code AS subject_id, CONCAT(sub.lec_units,'|',sub.lab_units) AS subject_units

        FROM subject_details sub LEFT JOIN subject_prospectus prosp ON sub.subject_prospectus_id = prosp.effective_school_year";

        if($prospectusID !== null){
            $sql .= " WHERE sub.subject_prospectus_id = :prospectusID;";

        }

        $query = $this->db->connect()->prepare($sql);
        if($prospectusID !== null){
            $query->bindParam(':prospectusID', $prospectusID);
        }


        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for semester dropdown
    public function fetchsemesterOption(){
        $sql = "SELECT 
                sem.semester AS semester_id,
                sem.description AS semester_desc,
                sem.school_year AS school_year

            FROM semester sem
            
            ORDER BY school_year ASC;
            
            ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for semester dropdown
    public function fetchclassesOption(){
        $sql = "SELECT 
                CONCAT(class.class_id,' ', class.subject_id) AS class_sub,
                CONCAT(sub.lec_units,'|', sub.lab_units) AS subject_units,
                CONCAT(class.class_id,' ', class.subject_id, ' ',class.subject_type) AS class_display,
                class.class_id AS class_id,
                class.subject_type AS subject_type

            FROM class_details class LEFT JOIN subject_details sub ON class.subject_id = sub.subject_code
            GROUP BY class.class_id
            ORDER BY class_id ASC;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

     //for filter dropdown search Teacher
    public function fetchteacherOption(){
        $sql = "SELECT fac.faculty_id AS faculty_id, CONCAT(acc.last_name,', ',acc.first_name) AS teacher_name 
        FROM faculty_list fac 
        LEFT JOIN user_list user ON fac.user_id = user.user_id
        LEFT JOIN account acc ON user.user_id = acc.account_id;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown subject code
    public function fetchsubjectnameOption(){
        $sql = " SELECT DISTINCT subject_code FROM subject_details;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }
    
    //for filter dropdown subject type
    public function fetchsubtypeOption(){
        $sql = " SELECT std.type AS subject_type FROM subject_type_description std;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown section
    public function fetchsectionOption(){
        $sql = "SELECT * FROM section_details;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    //for filter dropdown Day
    public function fetchdayOption(){
        $sql = "SELECT section_name FROM section_details 
        ;";
        $query = $this->db->connect()->prepare($sql);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

    // Method to fetch sections based on the selected course ID
    public function fetchSectionsByCourseId($courseId) {
        $sql = "SELECT section_name FROM section_details WHERE course_id = :course_id";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(':course_id', $courseId);
        $data = null;
        if ($query->execute()) {
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
        }
        return $data;
    }

}

?>

