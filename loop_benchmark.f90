program ParallelLoop
    use iso_c_binding
    use omp_lib
    implicit none
    
    integer(C_INT) :: x, count_loops_clone
    integer(C_INT), parameter :: iterations = 5000000
    integer(C_INT) :: i, total
    
    count_loops_clone = 0
    
    !$OMP PARALLEL PRIVATE(x, i) SHARED(count_loops_clone)
    
    x = 0
    do
        !$OMP DO
        do i = 1, iterations
            call c_f_pointer(c_loc(x), x)
            x = 0
        end do
        !$OMP END DO
        
        !$OMP ATOMIC
        count_loops_clone = count_loops_clone + iterations
        !$OMP END ATOMIC
        
    end do
    
    !$OMP END PARALLEL
    
    ! Print the total count after the loop
    total = count_loops_clone
    print *, "Total Count: ", total
    
end program ParallelLoop
