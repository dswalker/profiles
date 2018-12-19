<?php

namespace App\Service;

use App\Entity\School;
use App\Entity\Department;
use App\Entity\College;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Spreadsheet loader
 * 
 * @author dwalker@calstate.edu
 */
class SpreadsheetLoader
{
    /**
     * @var EntityManager
     */
    private $entity_manager;
    
    /**
     * New SpreadsheetLoader
     * 
     * @param EntityManager $entity_manager
     */
    public function __construct(EntityManagerInterface $entity_manager)
    {
        $this->entity_manager = $entity_manager;
    }
    
    /**
     * Load department spreadsheet
     * 
     * @param string $file  location of file
     */
    public function loadDepartments(string $file) : void
    {
        // load the file
        $spreadsheet = IOFactory::load($file);
        
        // keep track of colleges and schools
        
        $colleges = [];
        $schools = [];
        
        foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
            
            if ($row->getRowIndex() == 1) continue; // header
            
            // keep track of the college and school name for this dept
            
            $college_name = "";
            $school_name = "";
            
            foreach ($row->getCellIterator() as $cell) {
                
                $column = substr($cell->getCoordinate(), 0, 1);
                $value = $cell->getValue();
                
                if ($value == "") continue;
                
                // college
                
                if ($column == 'A') {
                    $college_name = $value;
                    
                    // first time we've seen this college, so store it
                    if (! array_key_exists($value, $colleges)) {
                        $college = new College();
                        $college->setName($college_name);
                        $colleges[$college_name] = $college;
                        $this->entity_manager->persist($college);
                    }
                }
                
                // school
                
                if ($column == 'B') {
                    $school_name = $value;
                    
                    // first time we've seen this school, so store it
                    if (! array_key_exists($value, $schools)) {
                        $school = new School();
                        $school->setName($school_name);
                        $school->setCollege($colleges[$college_name]);
                        $schools[$school_name] = $school;
                        $this->entity_manager->persist($school);
                    }
                }
                
                // department
                
                if ($column == 'C') {
                    
                    $department = new Department();
                    $department->setName($value);
                    $department->setSpreadsheetId($row->getRowIndex());
                    
                    // attach the department to the school, if defined
                    // otherwise to the college
                    
                    if (array_key_exists($school_name, $schools)) {
                        $department->setSchool($schools[$school_name]);
                    } elseif (array_key_exists($college_name, $colleges)) {
                        $department->setCollege($colleges[$college_name]);
                    }
                    
                    $this->entity_manager->persist($department);
                }
            }
        }
        
        $this->entity_manager->flush();
    }
}
